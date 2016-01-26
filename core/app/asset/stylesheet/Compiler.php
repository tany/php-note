<?php

namespace app\asset\stylesheet;

trait Compiler {

    protected $mixin = [];

    public function parse() {
        $this->data = $this->parseRequire($this->data, $this->path);
    }

    protected function parseRequire($data, $path) {
        return preg_replace_callback('/\/\/= require (.*)$/m', function($m) use ($path) {
            $file = trim($m[1], '"\'');
            $file = stream_resolve_include_path($file);
            $this->updateTime(filemtime($file));
            if (!preg_match('/\.scss$/', $file)) return "__import__: {$file};";
            return $this->parseRequire($this->loadFile($file), $file);
        }, $data);
    }

    public function buildCss() {
        $data = $this->data;
        $data = preg_replace('/\/\*.*?\*\//s', '', $data); // comment
        $data = preg_replace('/^\s*\/\/.*$/m', '', $data); // remove comment line
        $data = strtr($data, '&', '+');
        $data = htmlspecialchars($data);
        $data = preg_replace('/\s*,(\r\n|\n)\s*/s', ', ', $data); // selector, selector
        $data = preg_replace('/\s*([^\{;\}]+)\s*\{/s', '<s name="$1">', $data);
        $data = preg_replace('/\}/', '</s>', $data);

        $doc = new \DOMDocument;
        $doc->loadXML("<xml>{$data}</xml>");
        $xml = $this->parseMixin($doc->firstChild);

        $doc = new \DOMDocument;
        $doc->loadXML("<xml>{$xml}</xml>");
        $css = $this->buildNode($doc->firstChild);
        $css = $this->applyImport($css);
        $css = htmlspecialchars_decode($css);
        //$css = preg_replace('/\n/m', '', $css); // line break

        return "{$css}\n";
    }

    protected function applyImport($data) {
        return preg_replace_callback('/__import__: (.*?);/', function($m) {
            return $this->loadFile($m[1]);
        }, $data);
    }

    protected function parseMixin(\DomNode $node) {
        $xml = '';
        foreach ($node->childNodes as $elem) {
            if ($elem instanceof \DOMText) {
                $value = $this->parseVariable($elem->nodeValue);
                $xml  .= $value;
                continue;
            }
            $name = $elem->getAttribute('name');
            $text = $elem->C14N();

            if ($mixin = preg_filter('/^\$([\w\-]+).*/', '$1', $name)) {
                $text = preg_replace('/^.*?>(.*)<.*/s', '$1', $text);
                $this->mixin[$mixin] = trim($this->applyMixin($text));
                continue;
            }
            $text = str_replace('>;', '>', $this->applyMixin($text));
            $xml .= $this->applyMixin($text);
        }
        return $xml;
    }

    protected function parseVariable($text) {
        return preg_replace_callback('/\$([\w\-]+):\s*(.*?);/m', function($m) {
            $this->mixin[$m[1]] = $m[2];
            return;
        }, $text);
    }

    protected function applyMixin($text) {
        return preg_replace_callback('/\$([\w\-]+)(?::\s*(.*?);)?/m', function($m) {
            $name  = $m[1];
            $value = $m[2] ?? null;

            if (isset($this->mixin[$name])) return $this->mixin[$name];
            return Mixin::call($name, $value);
        }, $text);
    }

    protected function currentPath($scope, $name) {
        if (!$name) return $scope;
        if ($name[0] === '@') return $scope;

        $names = preg_split('/\s*,\s*/', trim($name));
        if (!$scope) return $names;

        $paths = [];
        foreach ($scope as $p) {
            foreach ($names as $n) {
                if ($n[0] === '+') $paths[] = $p . substr($n, 1);
                elseif ($n[0] === ':') $paths[] = "{$p}{$n}";
                else $paths[] = "{$p} {$n}";
            }
        }
        return $paths;
    }

    protected function buildNode($node, $scope = []) {
        $name  = trim($node->getAttribute('name'));
        $paths = $this->currentPath($scope, $name);
        $path  = join(', ', $paths);
        $css   = [];

        foreach ($node->childNodes as $elem) {
            if ($elem instanceof \DOMText) {
                if ($text = $this->buildText($elem->nodeValue)) {
                    $css[] = $path ? "{$path} { {$text} }" : $text;
                }
                continue;
            }
            $css[] = $this->buildNode($elem, $paths);
        }

        $css = join("\n", $css);
        return strpos($name, '@') === 0 ? "{$name} {\n{$css}\n}" : $css;
    }

    protected function buildText($text) {
        $text = preg_replace('/\n\s*/m', ' ', trim($text));
        return $text;
    }
}

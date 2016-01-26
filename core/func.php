<?php

// debug
function dump($expression, ...$params) {
    return \app\Logger::dump($expression, ...$params);
}
function trace($expression, ...$params) {
    return \app\logger\Dump::trace($expression, ...$params);
}

// string
function h($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES);
}
function hbr($str) {
    return nl2br(htmlspecialchars((string)$str, ENT_QUOTES));
}
function extname($str) {
    return substr($str, strrpos($str, '.') + 1);
}
function camelize($str) {
    return str_replace(' ', '', ucwords(preg_replace('/[_\-]+/', ' ', preg_replace('/([^a-zA-Z0-9]+)/', '$1 ', $str))));
}
function underscore($str) {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str));
}
function hyphenate($str) {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $str));
}
function pretty_size($bytes) {
    if ($bytes >= 1073741824) $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    elseif ($bytes >= 1048576) $bytes = number_format($bytes / 1048576, 2) . ' MB';
    elseif ($bytes >= 1024) $bytes = number_format($bytes / 1024, 2) . ' KB';
    elseif ($bytes > 1) $bytes = $bytes . ' bytes';
    elseif ($bytes == 1) $bytes = $bytes . ' byte';
    else $bytes = '0 bytes';
    return $bytes;
}

// array
function array_kmap(callable $closure, array $array) {
    $ret = [];
    foreach ($array as $key => $val) $ret[] = $closure($key, $val);
    return $ret;
}

// file
function load_file($file) {
    ob_start();
    include $file;
    return ob_get_clean();
}
function load_yaml($file) {
    ob_start();
    include $file;
    return yaml_parse(ob_get_clean());
}
function ls($path) {
    $files = [];
    foreach (scandir($path) as $file) {
        if ($file[0] !== '.') $files[] = "{$path}/{$file}";
    }
    return $files;
}

// ---
function str_end($str, $needle) {
    return substr($str, strrpos($str, $needle)) === $needle;
    //return preg_match('/\Q' . $needle . '\E$/', $str);
}

// class
function get_parent_classes($class) {
    $classes = [];
    if ($parent = get_parent_class($class)) {
        $classes = array_merge(get_parent_classes($parent), $classes);
        $classes[] = $parent;
    }
    return $classes;
}
function class_uses_recursive($class, $autoload = true) {
    $traits = [];
    foreach (class_uses($class, $autoload) as $trait) {
        $traits = array_merge(class_uses_recursive($trait, $autoload), $traits);
        $traits[] = $trait;
    }
    return $traits;
}
function class_uses_real($class, $autoload = true) {
    $traits = [];
    foreach (get_parent_classes($class) as $parent) {
        $traits = array_merge($traits, class_uses_recursive($parent, $autoload));
    }
    return array_unique(array_merge($traits, class_uses_recursive($class, $autoload)));
}

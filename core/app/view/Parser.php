<?php

namespace app\view;

class Parser {

    public static function parse($code) {
        $code = preg_replace('/^\s*\/\/@.*$/m', '', $code);
        $code = preg_replace_callback('/^\s*@(\w+)( ?.*)$/m', 'self::syntax', $code);
        $code = preg_replace_callback('/\{\{(=?) (.*?) \}\}/s', 'self::variable', $code);
        $code = preg_replace_callback('/\{([=\$])(.*?)\}/s', 'self::variable', $code);
		//$code = preg_replace('/^\t+(<\?)/m', '$1', $code);// trimming
        return $code;
    }

    protected static function script($m) {
        return "<?php {$m[1]} ?>";
    }

    protected static function variable($m) {
        if ($m[1] === "=") return "<?php print({$m[2]}); ?>";
        return "<?php print(htmlspecialchars({$m[1]}{$m[2]}, ENT_QUOTES)); ?>";
    }

    protected static function syntax($m) {
        $key = $m[1];
        $exp = $m[2];

        if ($key === 'end')          return "<?php } ?>";
        elseif ($key === 'if')       return "<?php if (" . self::ifv($exp) . ") { ?>";
        elseif ($key === 'elif')     return "<?php } elseif (" . self::ifv($exp) . ") { ?>";
        elseif ($key === 'elseif')   return "<?php } elseif (" . self::ifv($exp) . ") { ?>";
        elseif ($key === 'else')     return "<?php } else { ?>";
        elseif ($key === 'foreach')  return "<?php foreach ({$exp}) { ?>";
        elseif ($key === 'for')      return "<?php for ({$exp}) { ?>";
        elseif ($key === 'continue') return "<?php continue; ?>";
        elseif ($key === 'break')    return "<?php break; ?>";
        elseif ($key === 'yield')    return "<?php print \$this->yield; ?>";
        elseif ($key === 'render')   return "<?php print \$this->renderPartial({$exp}); ?>";
        else return $m[0];
    }

    protected static function ifv($exp) {
        return preg_match('/^\$[\w\->\[\] ]+$/', $exp) ? "!empty({$exp})" : $exp;
    }
}

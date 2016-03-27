View
====

## Comment
~~~
// comment
~~~

## Variable
~~~
{$var}
{{ $var_or_func }}
{{= $var_or_func }}
~~~

## Syntax
~~~
@if $cond1
@elseif $cond2
@else
@end
~~~

## Loop
~~~
@for $i = 0; $i < 9; $i++
    @continue
@end

@foreach $array as $item
    @break
@end
~~~

## Native code
~~~
@php $line = 'code';
@php
    $line1 = 'code';
    $line2 = 'code';
@end
~~~

## Rendering
~~~
@yield
@render 'file'
~~~

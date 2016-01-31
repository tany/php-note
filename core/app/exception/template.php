<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $title ?></title>
<style>
*    { margin: 0; padding: 0; }
body { color: #666; font: 12px/1.4 Helvetica, Arial, Meiryo, sans-serif; }
h1   { padding: 15px 20px; color: #e39; font-size: 200%; }
h2   { padding: 10px 20px; xbox-shadow: 0 1px 1px #bbb; background: #e2e2e2; font-size: 140%; }
pre  { overflow-x: auto; margin: 10px 0; font: inherit; }
.no  { display: inline-block; min-width: 60px; margin: 0 10px 0 0; padding: 0 10px 0 0;
       border-right: 1px solid #ddd; text-align: right; }
.er  { display: inline-block; padding: 0 2px 0 0; color: #e07; }
.app-dump { padding-left: 20px; }
#app-stat { display: none; }
</style>
</head>
<body>

<h1><?= $title ?></h1>

<h2>Script: &nbsp;<?= "{$file}:{$line}" ?></h2>
<pre><?= $code ?? '' ?></pre>

<h2>Trace:</h2>
<pre><?= $trace ?></pre>

<h2>Dump:</h2>

</body>
</html>

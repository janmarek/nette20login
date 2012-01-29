<!doctype HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Přihlašování uživatelů v Nette 2.0</title>
	<link rel="stylesheet" href="style.css">
	<script src="jquery.js"></script>
		<script type="text/javascript" src="jush.js"></script>
	<script src="slidy.js"></script>
</head>
<body>
<?php
require __DIR__ . '/texy.min.php';
$texy = new Texy();
$texy->imageModule->root = '';
$texy->imageModule->fileRoot = __DIR__;
$html = $texy->process(file_get_contents('content.texy'));
$html = str_replace('<h1', '</div><div class="slide"><h1', $html);
//$html = str_replace('<code><pre>', '<pre><code>', $html);
//$html = str_replace('</pre></code>', '</code></pre>', $html);
//$html = str_replace('<code class="jush"><pre>', '<pre><code class="jush">', $html);
//$html = str_replace('<code class="jush-php"><pre>', '<pre><code class="jush-php">', $html);
echo "<div>$html</div>";
?>
</body>
</html>
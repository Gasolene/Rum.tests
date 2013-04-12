<!DOCTYPE html>
<html lang="<?=Rum::app()->lang?>" >
<head>
<meta charset="<?=Rum::app()->charset?>" />
<title><?=$title?></title>
</head>
<body>

<div id="page">
	<div id="header">
		<a id="logo"><img src="<?=  Rum::config()->uri?>/resources/images/logo.png"/></a>
		<ul id="nav">
			<li><a href="<?=\Rum::uri('form')?>">Home</a></li>
		</ul>
		<a href="/logout/" class="login">Logout</a>
	</div>

	<div id="body">
		<div id="content" class="wide">
			<?=Rum::messages()?>
			<?php $this->content() ?>
		</div>
		<div style="clear:both"></div>
	</div>

	<div class="push"></div>
</div>

<div id="footer">
	<div id="footer_content">
		<span><strong>Framework Version:</strong> <?php echo \System\Base\FRAMEWORK_VERSION_STRING ?></span>
		<ul id="footer_nav">
			<li class="last"><a href="/">Home</a></li>
		</ul>
	</div>
</div>

</body>
</html>

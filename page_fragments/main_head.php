<?php
if(!isset($PAGE_TITLE)||!isset($PAGE_ID))
{
	throw new Exception("Client page attempted to be loaded without title or ID");
}
?><!DOCTYPE html>
<html lang="en-US">
<head>
	<title><?=$PAGE_TITLE;?> - Auramgold</title>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
	<meta name="author" content="Lauren Smith"/>
	<meta name="description" content="<?=$PAGE_DESCRIPTION??'An Auramgold Website page.';?>"/>
	<meta name="keywords" content="auramgold,lauren,website,accessibility,stories,<?=$PAGE_EXT_KEYWORDS??'';?>"/>
	<link rel="shortcut icon" href="/favicon.ico?m=<?=filemtime('favicon.ico');?>" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="/main.css?m=<?=filemtime('main.css');?>"/><?php
	if(isset($PAGE_STYLES)):
		foreach($PAGE_STYLES as $sheet):
	?>
	<link rel="stylesheet" type="text/css" href="/<?="$sheet.css?m=".filemtime($sheet.'.css');?>"<?php
		endforeach;
	endif;?>
</head>
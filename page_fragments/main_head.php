<?php
if(!isset($PAGE_TITLE)||!isset($PAGE_ID))
{
	throw new Exception("Client page attempted to be loaded without title or ID");
}
if(!isset($PAGE_STYLES))
{
	$PAGE_STYLES = array();
}
switch($_COOKIE['style'])
{
	case 'dark':
		$style = 'dark';
		break;
	case 'dark-blue':
		$style = 'dark-blue';
		break;
	case 'light-blue':
		$style = 'light-blue';
		break;
	default:
		$style = 'light';
}
if($style != 'light')
{
	$PAGE_STYLES[] = $style;
}
?><!DOCTYPE html>
<html lang="en-US">
<head>
	<title><?=$PAGE_TITLE;?> - auramgold.com</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
	<meta name="author" content="Lauren (auramgold)"/>
	<meta name="description" content="<?=$PAGE_DESCRIPTION??'An auramgold.com page.';?>"/>
	<meta name="keywords" content="auramgold,lauren,website,accessibility,stories,<?=$PAGE_EXT_KEYWORDS??'';?>"/>
	<link rel="shortcut icon" href="/favicon.ico?m=<?=filemtime('favicon.ico');?>" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="/main.css?m=<?=filemtime('main.css');?>"/><?php
	if(isset($PAGE_STYLES)):
		foreach($PAGE_STYLES as $sheet):
	?>
	<link rel="stylesheet" type="text/css" href="/<?="$sheet.css?m=".filemtime($sheet.'.css');?>"><?php
		endforeach;
	endif;?>
</head>
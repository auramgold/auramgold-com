<?php
if(!isset($PAGE_TITLE)||!isset($PAGE_ID))
{
	throw new Exception("Client page attempted to be loaded without title or ID");
}
if(!isset($PAGE_STYLES))
{
	$PAGE_STYLES = array();
}
require_once 'styles_list.php';
if(isset($_COOKIE['style']))
{
	foreach($STYLES_LIST as $file => $name)
	{
		if($_COOKIE['style'] === $file)
		{
			$style = $file;
			break;
		}
	}
}
$style = $style ?? 'light';
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
	<meta name="author" content="Wyatt fractal Starlight"/>
	<meta name="description" content="<?=$PAGE_DESCRIPTION??'An auramgold.com page.';?>"/>
	<meta name="keywords" content="auramgold,fractal,wyatt,website,stories,eeveeauramgold,<?=$PAGE_EXT_KEYWORDS??'';?>"/>
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@eeveeauramgold"/>
	<meta name="twitter:title" content="<?=$PAGE_TITLE;?>"/>
	<meta name="twitter:description" content="<?=$PAGE_DESCRIPTION;?>"/>
	
	<link rel="shortcut icon" href="/favicon.ico?m=<?=filemtime('/home/public/favicon.ico');?>" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="/styles/main.css?m=<?=filemtime('/home/public/styles/main.css');?>"/><?php
	if(isset($PAGE_STYLES)):
		foreach($PAGE_STYLES as $sheet):
	?>
	<link rel="stylesheet" type="text/css" href="/styles/<?="$sheet.css?m=".filemtime("/home/public/styles/$sheet.css");?>"><?php
		endforeach;
	endif;?>

</head>
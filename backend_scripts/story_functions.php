<?php
namespace story;
require_once 'util_functions.php';

function iso8601(int $time): string
{
	date_default_timezone_set('UTC');
	return date('Y-m-d\TH:i:s\Z', $time);
}

function dateformat(int $time): string
{
	date_default_timezone_set('UTC');
	return date('Y-m-d @ H:i \U\T\C', $time);
}

function storybox(string $slug, string $title,string $aname,string $author_id, $desc, int $timestamp, array $tags = null, array $cws = null)
{
	?>
	<article class='story-heading'>
		<a href="https://www.auramgold.com/writing/<?=$slug;?>" class="no-display"><section class="box-title"><h3 class='story-name'><?=$title?></h3></section></a>
	<section class='story-info'>
		Written by <?=$aname?>
	</section>
	<section class='story-description'><?=$desc??"None."?></section>
	<section class='story-extra-info'>
		<section>
		Tags: <?php
	foreach($tags as &$tag)
	{
		$tag = str_replace(' ', '&nbsp;', $tag);
	}
	if(!is_null($tags) && count($tags)>0)
	{
		echo implode(', ',$tags);
	}
	else{ echo 'None'; }
					?>
	</section>
	<section>
		Modified: <time datetime="<?=\story\iso8601((int)$timestamp);?>"><?= \story\dateformat((int)$timestamp);?></time>
	</section>
	<section>
					Content Warnings: <?php
	foreach($cws as &$cw)
	{
		$cw = str_replace(' ', '&nbsp;', $cw);
	}
	if(!is_null($cws) && count($cws)>0)
	{
		echo implode(', ',$cws);
	}
	else{ echo 'None'; }
					?>
		</section>
	</section>
	</article><?php
}

function pagenav(string $currname, string $prevurl = NULL, string $nexturl = NULL, string $prevname = NULL, string $nextname = NULL, string $currurl = NULL)
{
	$currtag = is_null($currurl) ? 'div' : "a href='$currurl'";
	$currclose = is_null($currurl) ? 'div' : 'a';
	$prevname = $prevname ?? 'Prev';
	$nextname = $nextname ?? 'Next';
	?>
	<nav class="pagenav"><?php
	if(!is_null($prevurl)):?>
		<a href="<?=$prevurl;?>" class="no-display">&#x21D0<?=$prevname;?></a><?php
	endif;?>
		<<?=$currtag;?> class="pagenum no-display"><?=$currname;?></<?=$currclose;?>><?php
	if(!is_null($nexturl)):?>
		<a href="<?=$nexturl;?>" class="no-display"><?=$nextname;?>&#x21D2</a><?php
	endif;?>
	</nav>
	<?php
}
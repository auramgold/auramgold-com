<?php
require_once 'backend_scripts/database_functions.php';
require_once 'backend_scripts/story_functions.php';
require_once 'backend_scripts/util_functions.php';
$page = max(is_numeric($_GET['page'] ?? NULL) ? (int)$_GET['page'] : 1, 1);
$offset = 20 * ($page - 1);
$PAGE_ID = "SERIES";
$PAGE_TITLE = "Series Index";
$PAGE_DESCRIPTION = "All the story series on auramgold.com";
$PAGE_STYLES = ['stories'];

$series_data = array();

include 'page_fragments/main_head.php';?>
<body>
	<?php include 'page_fragments/main_header.php';?>
	<main id="body">
		<h1 class="handwriting">Index of Series</h1>
		<p>
			This is an index of the series on this site that stories can be
			 within. Series can be nested, such that a series may have many
			 subseries, but only one 'superseries'. This index lists the
			 'highest-level' series first, and will have a search implemented
			 when necessary.
		</p>
		<h2>Listed Series</h2><?php
	$parent_id = '';
	$curr_id = '';
	$parentquer = database\inst()->prepare('SELECT `name` FROM `series` WHERE `deleted`=0 AND `series_id`=?');
	$parentquer->bind_param('s', $parent_id);
		
	$seriesquer = "SELECT *,(`parent_id` IS NULL) AS toplevel, COUNT(`name`) AS count FROM `series`"
			. " WHERE `deleted`=0 ORDER BY toplevel DESC, `name` ASC LIMIT 20 OFFSET $offset";
	$seriesresu = database\inst()->query($seriesquer);
	while($row = $seriesresu->fetch_assoc()):
		print_r($row);
		$curr_id = $row['series_id'];
		$parent_id = $row['parent_id'];
		$name = $row['name'];
		$urlname = util\htmltourl($name);
		$desc = $row['description'];
	?>	
	<article class="story-heading">
		<a href="/series/series/<?=$urlname;?>/<?=$curr_id;?>">
		<section class="box-title">
			<h3><?=$name?></h3>
		</section>
		</a>
		<section class='story-description'><?=$desc;?></section>
		<section class='story-info'>
					Parent Series: <?php
	if(!is_null($parent_id))
	{
		$parentname = '';
		if(isset($series_data[$parent_id]))
		{
			$parentname = $series_data[$parent_id];
		}
		else
		{
			$parentquer->execute();
			$parentname = $parentquer->get_result()->fetch_assoc()['name'];
		}
		$parenturlname = util\htmltourl($parentname);
		?><a href="/series/series/<?=$parenturlname;?>/<?=$parent_id;?>"><?=$parentname;?></a><?php
	}
	else
	{
		echo 'None';
	}
	?>			
		</section>
	</article>
	<?php endwhile;
	$seriescount = database\count('series');
	$prevurl = $page > 1 ? "/series/page/".($page-1) : NULL;
	$nexturl = $seriescount > ($offset + 20) ? "/series/page/".($page+1) : NULL;

	story\pagenav("Page $page",$prevurl,$nexturl);?>
	</main>
</body>
</html>

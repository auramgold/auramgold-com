<?php
require_once 'backend_scripts/database_functions.php';
require_once 'backend_scripts/story_functions.php';
$id = $_GET['id'] ?? NULL;
$allowable = !is_null($id);
if($allowable)
{
	$id = strtoupper($id);
	$prepquer = database\inst()->prepare('SELECT * FROM `stories` WHERE `story_id`=?');
	$prepquer->bind_param("s", $id);
	$prepquer->execute();
	$resu = $prepquer->get_result();
	$data = $resu->fetch_assoc();
	if(!$data)
	{
		$allowable = false;
	}
	$prepquer->close();
}
if(!$allowable)
{
	echo "<meta http-equiv=\"refresh\" content=\"0;URL='/stories'\" />";
	die;
}

$tagquer = database\inst()->prepare('SELECT * '
	. 'FROM `story_tags` JOIN `tags` '
	. 'ON (`story_tags`.`tag_id` = `tags`.`tag_id`) '
	. 'WHERE `story_tags`.`story_id`=?');
$tagquer->bind_param("s", $id);
$tagquer->execute();
$tagresu = $tagquer->get_result();
$tagcomma = false;
$tags = '';
$tagquer->close();

while($row = $tagresu->fetch_assoc())
{
	if(!$comma){$comma = true;}
	else{		$tags .= ', ';}
	$tags .= $row['name'];
}
if(!$comma){$tags = NULL;}

$authorresp = database\inst()->query("SELECT `name` FROM `authors` "
		. "WHERE `author_id` = '{$data['author_id']}'");
$authordata = $authorresp->fetch_assoc();

$PAGE_ID = "STORY/$id";
$PAGE_TITLE = $data['title']." by $authordata[name]";
$PAGE_DESCRIPTION = $data['description'];
$PAGE_STYLES = ['stories'];
$PAGE_EXT_KEYWORDS = $tags??'';
include 'page_fragments/main_head.php';?>
<body>
	<?php include 'page_fragments/main_header.php';?>
	<article id="body" class="main-border"><?php
	$prevname = NULL;
	$prevurl = NULL;
	$nextname = NULL;
	$nexturl = NULL;
	$seriesname = NULL;
	$seriesurl = NULL;
	
	if(!is_null($data['series_id']))
	{
		$sequencequer = database\inst()->prepare('SELECT `title` FROM `stories` WHERE `story_id`=?');
		$bound_id = '';
		$sequencequer->bind_param('s', $bound_id);
		if(!is_null($data['prev_id']))
		{
			$bound_id = $data['prev_id'];
			$sequencequer->execute();
			$prevname = $sequencequer->get_result()->fetch_assoc()['title'];
			$prevurlname = util\htmltourl($prevname);
			$prevurl = "/stories/story/$prevurlname/$bound_id";
		}
		if(!is_null($data['next_id']))
		{
			$bound_id = $data['next_id'];
			$sequencequer->execute();
			$prevname = $sequencequer->get_result()->fetch_assoc()['title'];
			$nexturlname = util\htmltourl($nextname);
			$nexturl = "/stories/story/$nexturlname/$bound_id";
		}
		
		$seriesname = database\inst()->query("SELECT `name` FROM `series` WHERE `series_id` = '$data[series_id]'")->fetch_assoc()['name'];
		$seriesurlname = util\htmltourl($seriesname);
		$seriesname .= ' series';
		$seriesurl = "/series/series/$seriesurlname/$data[series_id]";
		$sequencequer->close();
		
		story\pagenav($seriesname, $prevurl, $nexturl, $prevname, $nextname, $seriesurl);
	}
		?>
		<header id="main-story-heading" class='story-heading'>
			<section class="box-title">
				<h1><?=$data['title'];?></h1>
			</section>
			<section class='story-info'>
				<?php
					$urlname = urlencode(htmlspecialchars_decode($authordata['name']));
				?>Written by <a href='/authors/author/<?=$urlname;?>/<?=$data['author_id'];?>/'><?=$authordata['name'];?></a>
			</section>
			<section class='story-description'><?=$data['description'];?></section>
			<section class='story-extra-info'>
				<section>
					Tags: <?=$tags??'None.';?>
				</section>
				<section>
					Modified: <?= \story\iso8601((int)$data['modified_time']);?>
				</section>
				<section>
					Content Warnings: <?php
	$prepquer = database\inst()->prepare('SELECT * '
			. 'FROM `story_content_warnings` JOIN `content_warnings` '
				. 'ON (`story_content_warnings`.`cw_id` = `content_warnings`.`cw_id`) '
			. 'WHERE `story_content_warnings`.`story_id`=?');
	$prepquer->bind_param("s", $id);
	$prepquer->execute();
	$resu = $prepquer->get_result();
	$comma = false;
	while($row = $resu->fetch_assoc())
	{
		if(!$comma)
		{
			$comma = true;
		}
		else
		{
			echo ', ';
		}
		echo $row['name'];
	}
	if(!$comma){ echo 'None.'; }
					?>
				</section>
			</section>
		</header>
		<br/>
		<div>
		<?php include "story_files/{$data['location']}";
		
		if(!is_null($data['series_id']))
		{
			story\pagenav($seriesname, $prevurl, $nexturl, $prevname, $nextname, $seriesurl);
		}
		?>
		</div>
		<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">
			<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" />
		</a>
		<br/>
		<small>
		<span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/Text" property="dct:title" rel="dct:type"><?=$data['title'];?></span>
		 by <a xmlns:cc="http://creativecommons.org/ns#" 
			 href="<?='https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>" 
			 property="cc:attributionName" rel="cc:attributionURL"><?=$authordata['name'];?></a>
		 is licensed under a
		 <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a>.
		</small>
	</article>
</body>
</html>
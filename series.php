<?php
require_once 'backend_scripts/database_functions.php';
require_once 'backend_scripts/story_functions.php';
require_once 'backend_scripts/util_functions.php';
$id = $_GET['id'] ?? NULL;
$page = max(is_numeric($_GET['page'] ?? NULL) ? (int)$_GET['page'] : 1, 1);
$offset = 20 * ($page - 1);
$allowable = !is_null($id);
if($allowable)
{
	$id = strtoupper($id);
	$prepquer = database\inst()->prepare('SELECT * FROM `series` WHERE `series_id`=?');
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
	header("HTTP/1.1 404 Not Found");
	include 'errors/404.php';
	die;
}
$PAGE_ID = "SERIES/$id";
$PAGE_TITLE = $data['name'];
$PAGE_DESCRIPTION = $data['description'];
$PAGE_STYLES = ['stories'];
include 'page_fragments/main_head.php';?>
<body>
	<?php include 'page_fragments/main_header.php';?>
	<main id="body" class="main-border">
		<header id="main-story-heading" class='story-heading'>
			<section class="box-title">
				<h1><?=$data['name'];?></h1>
			</section>
			<section class='story-description'><?=$data['description'];?></section>
			<section class='story-extra-info'>
				<section>
					Parent Series: <?php
	if(!is_null($data['parent_id']))
	{
		$parentquer = "SELECT `name` FROM `series` WHERE `series_id` = '$data[parent_id]'";
		$parentresu = database\inst()->query($parentquer);
		$parentname = $parentresu->fetch_assoc()['name'];
		$parenturlname = util\htmltourl($name);
		?><a href="/series/series/<?=$parenturlname;?>/<?=$data['parent_id'];?>"><?=$parentname;?></a><?php
	}
	else
	{
		echo 'None';
	}
	?>
				</section>
				<section>
					Child Series: <?php
	$childquer = "SELECT `series_id`,`name` FROM `series` WHERE `parent_id`='$id'";
	$childresu = database\inst()->query($childquer);
	$comma = false;
	while($row = $childresu->fetch_assoc())
	{
		$childurlname = util\htmltourl($row['name']);
		if($comma)
		{
			echo ', ';
		}
		else
		{
			$comma = true;
		}
		echo "<a href='/series/series/$childurlname/$row[id]'>$data[name]</a>";
	}
	if(!$comma)
	{
		echo 'None';
	}
	?>
				</section>
			</section>
		</header>
		<h2>Stories in <?=$data['name'];?></h2>
		<!--This stuff is auto formatted by the backend, so if the indentation looks weird, that's why-->
		<?php
		$story_id = '';
		$tagquer = database\inst()->prepare
		(
			'SELECT `name` FROM `story_tags` JOIN `tags` ON `story_tags`.`tag_id`'
				. ' = `tags`.`tag_id` WHERE `story_id` = ?'
		);
		$tagquer->bind_param('s', $story_id);
		
		$cwquer = database\inst()->prepare
		(
			'SELECT `name` FROM `story_content_warnings` JOIN `content_warnings` ON `story_content_warnings`.`cw_id`'
				. ' = `content_warnings`.`cw_id` WHERE `story_id` = ?'
		);
		$cwquer->bind_param('s', $story_id);
		
		$storyquer = "SELECT `stories`.*, (`prev_id` IS NULL) AS notfirst, COUNT(`title`) AS count,"
				. " `authors`.`name` AS name FROM `stories`"
				. " JOIN `authors` ON (`stories`.`author_id`=`authors`.`author_id`)"
				. " WHERE `series_id` = '$id'"
				. " ORDER BY notfirst DESC, `modified_time` DESC LIMIT 20 OFFSET $offset";
		$storyresu = database\inst()->query
		(
			$storyquer
		);
		while($row = $storyresu->fetch_assoc())
		{
			$story_id = $row['story_id'];
			
			$tags = array();
			$tagquer->execute();
			$tagresu = $tagquer->get_result();
			while($tagrow = $tagresu->fetch_assoc())
			{
				$tags[] = $tagrow['name'];
			}
			
			$cws = array();
			$cwquer->execute();
			$cwresu = $cwquer->get_result();
			while($cwrow = $cwresu->fetch_assoc())
			{
				$cws[] = $cwrow['name'];
			}
			
			story\storybox($story_id, $row['title'], $row['name'], $row['author_id'], $row['description'], $row['modified_time'], $tags, $cws);
		}
		
		$storycount = database\count('stories',"`series_id` = '$id'");
		
		$encname = util\htmltourl($data['name']);
		$prevurl = $page > 1 ? "/series/series/$encname/$id/page/".($page-1) : NULL;
		$nexturl = $storycount > ($offset + 20) ? "/series/series/$encname/$id/page/".($page+1) : NULL;
		
		story\pagenav("Page $page",$prevurl,$nexturl);
		?>
	</main>
</body>
</html>

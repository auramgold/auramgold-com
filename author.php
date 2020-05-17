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
	$prepquer = database\inst()->prepare('SELECT * FROM `authors` WHERE `author_id`=?');
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
$PAGE_ID = "AUTHOR/$id";
$PAGE_TITLE = $data['name'] . "'s stories";
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
			<section class='story-info'>
				Pronouns: <?=$data['pronouns'];?>
			</section>
		</header>
		<h2>Stories by <?=$data['name'];?></h2>
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
		
		$storyquer = "SELECT *, (`prev_id` IS NULL) AS notfirst FROM `stories` WHERE `author_id` = '$id'"
				. " ORDER BY notfirst DESC, `modified_time` DESC LIMIT 20 OFFSET $offset";
		$storyresu = database\inst()->query
		(
			$storyquer
		);
		while($row = $storyresu->fetch_assoc())
		{
			$story_id = $row['story_id'];
			$slug = $row['slug'];
			
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
			
			story\storybox($slug, $row['title'], $data['name'], $id, $row['description'], $row['modified_time'], $tags, $cws);
		}
		
		$storycount = database\count('stories', "`author_id` = '$id'");
		
		$encname = util\htmltourl($data['name']);
		$prevurl = $page > 1 ? "/authors/author/$encname/$id/page/".($page-1) : NULL;
		$nexturl = $storycount > ($offset + 20) ? "/authors/author/$encname/$id/page/".($page+1) : NULL;
		
		story\pagenav("Page $page",$prevurl,$nexturl);
		?>
	</main>
</body>
</html>

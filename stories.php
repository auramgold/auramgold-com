<?php
require_once 'backend_scripts/database_functions.php';
require_once 'backend_scripts/story_functions.php';
require_once 'backend_scripts/util_functions.php';
$page = max(is_numeric($_GET['page'] ?? NULL) ? (int)$_GET['page'] : 1, 1);
$offset = 20 * ($page - 1);
$PAGE_ID = "STORIES";
$PAGE_TITLE = "Story Index";
$PAGE_DESCRIPTION = "The index of stories on auramgold.com";
$PAGE_STYLES = ['stories'];
include 'page_fragments/main_head.php';?>
<body>
	<?php include 'page_fragments/main_header.php';?>
	<main id="body">
		<h1 class="handwriting">Stories Index</h1>
		<p>This is an automatically-generated index of the stories we host on
			 this site. A search bar will be added once there is more here such that
			 a search function would be necessary.
		</p>
		<!--TODO: Implement a search-->
			
		<h2>Recent Stories</h2><?php
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
	
	$storyquer = "SELECT `stories`.*, (`prev_id` IS NULL) AS notfirst, `authors`.`name` FROM `stories`"
			. " JOIN `authors` ON (`stories`.`author_id`=`authors`.`author_id`) WHERE `modified_time` != 0 AND `deleted` = 0"
			. " ORDER BY notfirst DESC, `modified_time` DESC, `title` ASC LIMIT 20 OFFSET 0";
	$storyresu = database\inst()->query($storyquer);
	
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

		story\storybox($slug, $row['title'], $row['name'], $row['author_id'], $row['description'], $row['modified_time'], $tags, $cws);
	}
	
	$storycount = database\count('stories','`modified_time` != 0');
	
	$prevurl = $page > 1 ? "/stories/page/".($page-1) : NULL;
	$nexturl = $storycount > ($offset + 20) ? "/stories/page/".($page+1) : NULL;

	story\pagenav("Page $page",$prevurl,$nexturl);
	
	
	?>
	</main>
</body>
</html>

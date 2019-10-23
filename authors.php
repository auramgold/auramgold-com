<?php
require_once 'backend_scripts/database_functions.php';
require_once 'backend_scripts/story_functions.php';
require_once 'backend_scripts/util_functions.php';
$page = max(is_numeric($_GET['page'] ?? NULL) ? (int)$_GET['page'] : 1, 1);
$offset = 20 * ($page - 1);
$PAGE_ID = "AUTHORS";
$PAGE_TITLE = "Author Index";
$PAGE_DESCRIPTION = "The list of authors of stories on auramgold.com";
$PAGE_STYLES = ['stories'];
include 'page_fragments/main_head.php';?>
<body>
	<?php include 'page_fragments/main_header.php';?>
	<main id="body">
		<h1 class="handwriting">Index of Authors</h1>
		<p>
			This is an index of the authors who have made content that is published
			 on this site. Authors are listed with their own pronoun preference,
			 as described by them, to better aid in properly referring to them.
			 A search function will be added to this page if needed.
		</p>
		<h2>Listed Authors</h2><?php
	$authorquer = "SELECT *, COUNT(`name`) AS count FROM `authors`"
			. " WHERE 1 ORDER BY `name` ASC LIMIT 20 OFFSET $offset";
	$authorresu = database\inst()->query($authorquer);
	while($row = $authorresu->fetch_assoc()):
		$urlname = util\htmltourl($row['name']);
	?>	
	<article class='story-heading'>
		<a class="no-display" href="<?="/authors/author/$urlname/$row[author_id]";?>">
		<section class="box-title">
			<h1><?=$row['name'];?></h1>
		</section>
		</a>
		<section class='story-description'><?=$row['description'];?></section>
		<section class='story-info'>
			Pronouns: <?=$row['pronouns'];?>
		</section>
	</article>
	<?php endwhile;
	
	$authorcount = database\count('authors');
	
	$prevurl = $page > 1 ? "/author/page/".($page-1) : NULL;
	$nexturl = $authorcount > ($offset + 20) ? "/author/page/".($page+1) : NULL;

	story\pagenav("Page $page",$prevurl,$nexturl);?>
	</main>
</body>
</html>

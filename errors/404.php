<?php
header("HTTP/1.1 404 Not Found");
$PAGE_ID = "404_NOT_FOUND";
$PAGE_TITLE = "404 Not Found";
$PAGE_DESCRIPTION = "A 404 error due to a malformed link.";
include '/home/public/page_fragments/main_head.php';?>
<body>
	<?php include '/home/public/page_fragments/main_header.php';?>
	<main id="body">
		<h1 class="handwriting">404 Not Found Error</h1>
		<p>
			This page is shown when my server receives a link that it does not
			know how to properly handle. If you found this page through someone
			linking to this site, tell them that their link is incorrect. If
			you got to this page through a link somewhere else on the site,
			please contact the me so that I may fix that.
		</p>
		<img src="/decamark.png" alt=""/>
		<p>
			Regardless of either of those cases, if you want to get back to the
			actual site, you may navigate there with the bar at the top of the
			page.
		</p>
	</main>
</body>
</html>
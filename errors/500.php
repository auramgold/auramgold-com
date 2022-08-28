<?php
header("HTTP/1.1 500 Internal Server Error");
$PAGE_ID = "500_INTERNAL_SERVER_ERROR";
$PAGE_TITLE = "500 Internal Server Error";
$PAGE_DESCRIPTION = "A 500 error due to a server problem.";
include '/home/public/page_fragments/main_head.php';?>
<body>
	<?php include '/home/public/page_fragments/main_header.php';?>
	<main id="body">
		<h1 class="handwriting">500 Internal Server Error</h1>
		<p>
			This page is shown when we have an unrecoverable error occur in our
			code. This is most likely our fault, and should hopefully be fixed
			on a page refresh. We are sorry for the inconvenience.
		</p>
		<img src="/decamark.png" alt=""/>
		<p>
			If this error persists beyond a page refresh, however, please do
			inform the webmaster, as something has likely gone much more deeply
			wrong on our end.		
		</p>
	</main>
</body>
</html>
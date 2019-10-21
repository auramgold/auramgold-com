<?php
if(isset($_POST))
{
	$exptime = time() + (20*365*24*3600);
	if(isset($_POST['style']))
	{
		setcookie('style',urlencode($_POST['style']),$exptime,'/');
		$_COOKIE['style'] = $_POST['style'];
	}
}
$PAGE_ID = "SETTINGS";
$PAGE_TITLE = "Configuration";
$PAGE_DESCRIPTION = "Configure viewing settings for auramgold.com";
include 'page_fragments/main_head.php';?>
<body>
	<?php include 'page_fragments/main_header.php';?>
	<main id="body">
		<h1>Settings</h1>
		<p>
			This is where settings for this website are configured. Using any of
			 these settings is optional, and they are stored in cookies when you
			 set them, so A: they may expire after a very long time, and B: for 
			 EU compliance, it is entirely optional to use these cookies when
			 consuming this site's content. Notably, even if you do use these
			 settings, <strong>no information is stored
			 server-side from this page</strong>.
		</p>
		<?=$foobar;?>
		<form method='post'>
			<label>Color Scheme:
				<select name='style'>
					<option value='light' <?=$style=='light'?'selected':''?>>Light Theme</option>
					<option value='dark' <?=$style=='dark'?'selected':''?>>Dark Theme</option>
				</select>
			</label>
			<button type='submit'>Change Settings</button>
		</form>
	</main>
</body>
</html>

<?php
if(!isset($PAGE_ID))
{
	throw new Exception("Client page attempted to be loaded without ID");
}
$pages = [
	'Main' => 'https://www.auramgold.com/',
	'Fiction' => 'https://www.auramgold.com/stories',
	//'Authors' => 'https://www.auramgold.com/authors',
	//'Series' => 'https://www.auramgold.com/series',
	'Settings' => 'https://www.auramgold.com/settings',
	'Licensing' => 'https://www.auramgold.com/licensing'
];
?>
	<header id="top">
		<div id="headerwrap">
			<div id="toplogo" class="handwriting">auramgold</div>
			<div role="presentation" style="flex-grow:0.25;max-height:0"></div>
			<nav role="navigation">
			<ul id="header-nav-list" class='no-display'>
<?php
			foreach($pages as $name => $address):?>
				<li class='nav-item'>
				<a class="no-display" href='<?=$address;?>'><?=$name;?></a>
				</li>
<?php
			endforeach; ?>
			</ul>
			</nav>
		</div>
	</header>
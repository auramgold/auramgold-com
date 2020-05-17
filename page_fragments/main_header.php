<?php
if(!isset($PAGE_ID))
{
	throw new Exception("Client page attempted to be loaded without ID");
}
$pages = [
	'Main' => '/',
	'Writing' => '/stories',
	//'Authors' => '/authors',
	//'Series' => '/series',
	'Settings' => '/settings',
	'Licensing' => '/licensing'
];
?>
	<header id="top">
		<div id="headerwrap">
			<div id="toplogo" class="handwriting">Auramgold</div>
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
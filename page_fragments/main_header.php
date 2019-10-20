<?php
if(!isset($PAGE_ID))
{
	throw new Exception("Client page attempted to be loaded without ID");
}
?>
	<header id="top">
		<div id="headerwrap">
			<div id="toplogo" class="handwriting">Auramgold</div>
			<nav role="navigation">
				<a class="nav-item no-display" href='/'>Main</a>
				<a class="nav-item no-display" href="/stories">Stories</a>
				<a class='nav-item no-display' href='/authors'>Authors</a>
				<a class='nav-item no-display' href='/series'>Series</a>
				<a class="nav-item no-display" href="/licensing">Licensing</a>
			</nav>
		</div>
	</header>
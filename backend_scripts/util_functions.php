<?php
namespace util;

function htmltourl(string $what): string
{
	return urlencode(htmlspecialchars_decode($what));
}
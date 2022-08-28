<?php
namespace database;
$view_database_reference = NULL;

// Returns a random base 36 ID
function generateID(): string
{
	$ret = "";
	for($i = 0; $i < 4; $i++)
	{
		$curr = random_int(0,(36**4)-1);
		$curr = base_convert($curr, 10, 36);
		$curr = str_pad($curr,4,'0',STR_PAD_LEFT);
		$ret .= $curr;
	}
	return strtoupper($ret);
}

function &inst(): \mysqli
{
	global $view_database_reference;
	if(is_null($view_database_reference))
	{
		$attempts = 0;
		$errno = 0;
		$credentials = explode(',',file_get_contents('database_credentials.txt',true));
		
		do
		{
			$view_database_reference = new \mysqli("auramgold.db", $credentials[0],
				$credentials[1], "auramgold_stories");
			$errno = $view_database_reference->errno;
			$attempts++;
			if($attempts > 10)
			{
				include '../errors/500.php';
				die;
			}
		} while ($errno != 0);
	}
	return $view_database_reference;
}

function count(string $table, string $conditions = '1'): int
{
	$query = "SELECT COUNT(*) AS count FROM $table WHERE $conditions";
	$resp = inst()->query($query);
	return (int)$resp->fetch_assoc()['count'] ?? 0;
}

inst();
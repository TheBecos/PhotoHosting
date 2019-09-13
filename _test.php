<?php

error_reporting(E_ALL);
header("Pragma: no-cache");

include "../../settings/config.php";
include "../../settings/dbconnector.php";
include "../../settings/auth.php";
include "../../settings/func.php";


$user='8';

$q = "
			SELECT 
				".$sqlname."photo_list.id,
				".$sqlname."photo_list.datum as photo_date,
				".$sqlname."photo_list.name as name,
				".$sqlname."photo_list.des as des,
				".$sqlname."photo_list.fid as fid,
				".$sqlname."files.datum as file_date,
				".$sqlname."files.file as filename,
				".$sqlname."files.format as format
			FROM ".$sqlname."photo_list
			    LEFT JOIN ".$sqlname."files ON ".$sqlname."photo_list.fid = ".$sqlname."files.id
			WHERE 
				".$sqlname."photo_list.id > 0 AND
				".$sqlname."photo_list.iduser = '$user'
			ORDER BY photo_date DESC";
$list      = $db -> getAll($q);

$lists = [
    "list"  => array_values($list),
    "count" => count($list)
];

print_r($lists);


<?php

error_reporting(E_ALL);
//header("Pragma: no-cache");

include "../../settings/config.php";
include "../../settings/dbconnector.php";
include "../../settings/auth.php";

$users = [];
$users = $db->getAll("SELECT * FROM " . $sqlname . "user");

print_r($users);




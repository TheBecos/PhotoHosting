<?php

error_reporting(E_ALL);
//header("Pragma: no-cache");

include "../../settings/config.php";
include "../../settings/dbconnector.php";
include "../../settings/auth.php";

$name = "'yDptH45XBsc.jpg'";

$id = 0;

print $name;

$id = $db->getOne("SELECT id FROM " . $sqlname . "files WHERE file = " . $name);

print $id;




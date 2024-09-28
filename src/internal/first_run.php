<?php
include_once "database.php";
include_once "board.php";

if (!is_file(__DIR__ . "/../first_run"))
	die("The site has already been set up");

$database = new Database();
$database->setup_meta_info_database();
board_create($database, "def", "Default board");

unlink(__DIR__ . "/../first_run");
header("Location: /index.php");

<?php
include_once "../board.php";

if (!is_file(__DIR__ . "/../../first_run"))
	die("The site has already been set up");

$config_template_path = __DIR__ . "/../config.template.php";
$config_path = __DIR__ . "/../config.php";

$config_template_file = fopen($config_template_path, "r");
$config_template = fread($config_template_file, filesize($config_template_path));

foreach ($_POST as $key => $value)
	$config_template = str_replace("%$key%", $value, $config_template);

$config_file = fopen($config_path, "w");
fwrite($config_file, $config_template);
fclose($config_file);

include_once "../database.php";

$database = new Database();
$database->manual_login($_POST["database_host"], $_POST["database_user"], $_POST["database_password"]);
$database->setup_meta_info_database();
board_create($database, "def", "Default board");

// if there are existing staff accounts in the db like when updating skip this step
if (count($database->get_staff_accounts()) <= 0)
	$database->write_staff_account("admin", hash("sha512", "admin"), "admin");

unlink(__DIR__ . "/../../first_run");
header("Location: /index.php");

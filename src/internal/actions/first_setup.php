<?php
include_once "../board.php";
include_once "../chaninfo.php";

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
include_once "../staff_session.php";

$database = new Database();
$database->setup_meta_info_database();
board_create($database, "def", "Default board");

// if there are existing staff accounts in the db like when updating skip this step
if (count(get_staff_accounts()) <= 0)
	write_staff_account("admin", hash("sha512", "admin"), "admin");

if (!file_exists(__DIR__ . "../chaninfo.json"))
{
	$chan_info = new ChanInfo();
	$chan_info->chan_name = "DeltaChan";
	$chan_info->welcome = "Welcome to DeltaChan!";
	$chan_info->rules = "Your rules go here.";
	chan_info_write($chan_info);
}

unlink(__DIR__ . "/../../first_run");
header("Location: /index.php");

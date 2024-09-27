<?php
echo var_dump($_POST);

include_once "../database.php";

$database = new Database();
$database->write_post(
	$_POST["board"], $_POST["is_reply"], $_POST["replies_to"], $_POST["title"], $_POST["comment"],
	"file goes here", $_SERVER["REMOTE_ADDR"], "pl", 0, ""
);

header("Location: /$board_id/");

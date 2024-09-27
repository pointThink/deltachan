<?php
$file_upload_dir = "uploads/";
$target_file = $file_upload_dir . basename($_FILES["file"]["name"]);

include_once "../database.php";

$database = new Database();
$database->write_post(
	$_POST["board"], $_POST["is_reply"], $_POST["replies_to"], $_POST["title"], $_POST["comment"],
	$target_file, $_SERVER["REMOTE_ADDR"], "pl", 0, ""
);

if (!is_dir(__DIR__ . "/../../" . $file_upload_dir))
	mkdir(__DIR__ . "/../../" . $file_upload_dir);

move_uploaded_file($_FILES["file"]["tmp_name"], __DIR__ . "/../../" . $target_file);

header("Location: /$board_id/");

<?php
include_once "../database.php";

$file_upload_dir = "uploads/";
$target_file = "";

$database = new Database();

$result = $database->write_post(
	$_POST["board"], $_POST["is_reply"], $_POST["replies_to"], $_POST["title"], $_POST["comment"],
	$_SERVER["REMOTE_ADDR"], "pl", 0, ""
);

if (!is_dir(__DIR__ . "/../../" . $file_upload_dir))
	mkdir(__DIR__ . "/../../" . $file_upload_dir);

if ($_FILES["file"]["size"] > 0)
{	
	$target_file = $file_upload_dir . "$result->board-" . strval($result->id) . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	move_uploaded_file($_FILES["file"]["tmp_name"], __DIR__ . "/../../" . $target_file);
	$database->update_post_file($result->board, $result->id, $target_file);
}

// header("Location: /$result->board/post.php?id=$result->id");

<?php
session_start();

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

	// create image thumbnail
	$image_data = file_get_contents(__DIR__ . "/../../" . $target_file);
	$image = imagecreatefromstring($image_data);
	
	$width = imagesx($image);
	$height = imagesy($image);

	$desired_width = 200;
	$desired_height = floor($height * ($desired_width / $width));

	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	imagecopyresampled($virtual_image, $image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	imagejpeg($virtual_image, __DIR__ . "/../../" . $file_upload_dir . "$result->board-$result->id-thumb.jpg");
}

// keep track of created posts
if (!isset($_SESSION["users_posts"]))
	$_SESSION["users_posts"] = array();

array_push($_SESSION["users_posts"], $result->id);

if ($_POST["is_reply"])
{
	$database->bump_post($result->board, $result->replies_to);
	header("Location: /$result->board/post.php?id=$result->replies_to");
}
else
	header("Location: /$result->board/post.php?id=$result->id");

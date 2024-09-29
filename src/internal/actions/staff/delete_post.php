<?php
include_once "../../database.php";
include_once "../../staff_session.php";

$board = $_POST["board"];
$id = $_POST["id"];

// echo var_dump($_POST);
echo $board . $post;

if (!staff_session_is_valid())
	die("You're not allowed to do that!");

$database = new Database();

// first delete the file
$post = $database->read_post($board, $id);
unlink(__DIR__ . "/../../../$post->image_file");

$database->remove_post($board, $id);

header("Location: /$board/");

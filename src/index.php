<?php
include_once "internal/database.php";
include_once "internal/board.php";

$database = new Database("localhost", "root", "root");
$database->setup_board_info_database();
board_create($database, "test2", "Test board 2", "This is a test board");

// $post = $database->read_post("test", 1);
// $post->display();


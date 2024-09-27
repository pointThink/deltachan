<?php
include_once "internal/database.php";

$database = new Database("localhost", "root", "root");
$database->setup_board_database("test");

$post = $database->read_post("test", 1);
$post->display();


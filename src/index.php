<?php
include_once "internal/database.php";

echo "Creating db<br>";
$database = new Database("localhost", "root", "root");
$database->setup_board_database("test");
$database->write_post("test", false, 0, "Title", "Post body", "image.png", "1.1.1.1", "pl", 0, "");

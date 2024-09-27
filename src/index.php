<?php
include_once "internal/database.php";

echo "Creating db<br>";
$database = new Database("localhost", "root", "root");
$database->setup_board_database("test");

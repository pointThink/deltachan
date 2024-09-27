<?php
include_once "database.php";

function board_create($database, $id, $title, $subtitle = "")
{
	$database->setup_board_database($id);
	$database->add_board_info_row($id, $title, $subtitle);
}

class Board
{
	public $board_id;
	public $board_title;
	public $board_subtitle;

	public $posts;

	private $database;

	public function __construct($database, $board_id)
	{
		$this->$database = $database;
	}
}

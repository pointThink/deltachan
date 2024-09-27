<?php
function create_board()
{
	
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

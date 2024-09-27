<?php
include_once "database.php";

function board_create($database, $id, $title, $subtitle = "")
{
	$database->setup_board_database($id);
	$database->add_board_info_row($id, $title, $subtitle);

	if (!is_dir("$id"))
	{
		mkdir("$id");
		$index_file = fopen("$id/index.php", "w");
		fwrite($index_file, "
<?php
\$board_id = '$id';
include __DIR__ . '/../board_index.php';
		");
	}
}

class Board
{
	public $board_id;
	public $board_title;
	public $board_subtitle;

	public $posts = array();
}

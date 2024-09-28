<?php
include_once "database.php";

function board_create($database, $id, $title, $subtitle = "")
{
	$database->setup_board_database($id);
	$database->add_board_info_row($id, $title, $subtitle);

	if (!is_dir(__DIR__ . "/../$id"))
	{
		mkdir(__DIR__ . "/../$id");

		$index_file = fopen(__DIR__ . "/../$id/index.php", "w");
		fwrite($index_file, "
<?php
\$board_id = '$id';
include __DIR__ . '/../board_index.php';
		");

		fclose($index_file);

		$post_view_file = fopen(__DIR__ . "/../$id/post.php", "w");
		fwrite($post_view_file, "
<?php
\$board_id = '$id';
include __DIR__ . '/../single_post_view.php';
		");

		fclose($post_view_file);

	}
}

class Board
{
	public $board_id;
	public $board_title;
	public $board_subtitle;

	public $posts = array();
}

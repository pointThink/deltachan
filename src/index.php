<!DOCTYPE html>
<?php
	include_once "internal/database.php";
	include_once "internal/board.php";

	$database = new Database("localhost", "root", "root");
	$database->setup_meta_info_database();

	// board_create($database, "test", "Test board", "This is a test board");
?>

<html>
	<head>
		<title>Index</title>
	</head>

	<body>
		<h1>Boards</h1>
		<?php
			$boards = $database->get_boards();

			foreach ($boards as $board)
			{
				echo "<a href=$board->id/>$board->title</a>";
			}
		?>
	</body>
</html>

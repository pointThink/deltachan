<!DOCTYPE html>
<?php
	include_once "internal/database.php";

	if (is_file("first_run"))
	{
		header("Location: /internal/first_run.php");
		die();
	}

	$database = new Database();
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

<?php
include "internal/database.php";
$database = new Database();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Board index</title>
		<link href="/internal/base_style.css" rel=stylesheet>
	</head>

	<body>
		<div id="board_index_header">
			<?php
				$database = new Database();

				$board = $database->get_board($board_id);

				echo "<h2>/$board->id/ - $board->title</h2>";
				echo "<h4>$board->subtitle</h3>";
			?>
		</div>

		<div id=posts>
			<?php
				$post = $database->read_post($board_id, $_GET["id"]);
				$post->display();
			?>
		</div>
	</body>
</html>

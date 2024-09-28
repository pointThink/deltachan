<?php
include "internal/database.php";
include "internal/ui.php";

$database = new Database();
$post = $database->read_post($board_id, $_GET["id"]);
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

		<div class=post_form>
			<?php
				(new PostForm("/internal/actions/post.php", "POST"))
					->add_text_area("Comment", "comment")
					->add_file("File", "file")
					->add_hidden_data("board", "$board_id")
					->add_hidden_data("is_reply", 1)
					->add_hidden_data("replies_to", $post->id)
					->finalize();
			?>
		</div>

		<div id=posts>
			<?php
				$post->display();
			?>
		</div>
	</body>
</html>

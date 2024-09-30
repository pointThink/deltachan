<?php
include_once "internal/database.php";
include_once "internal/ui.php";

$database = new Database();
$post = $database->read_post($board_id, $_GET["id"]);
?>

<!DOCTYPE html>
<html>
	<head>
		<?php
			$database = new Database();
			$board = $database->get_board($board_id);
			echo "<title>/$board->id/ - $post->title</title>";
			
			include "internal/link_css.php";
		?>

		<script src=/internal/post_display.js></script>
	</head>

	<body>
		<?php include "topbar.php" ?>

		<div id="board_index_header">
			<?php
				$mod_mode = staff_session_is_valid();

				echo "<h2>/$board->id/ - $board->title</h2>";
				echo "<h4>$board->subtitle</h4>";

				if ($mod_mode)
				{
					echo "<a href=/internal/actions/staff/logout.php>Logout from staff account</a><br><br>";
				}
			?>
		</div>

		<div class=post_form>
			<?php
				echo "<p id=reply_disclaimer>Replying to >$post->id</p>";

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
				$post->display($mod_mode);
			?>
		</div>

		<?php include "footer.php" ?>
	</body>
</html>

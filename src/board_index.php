<?php
include_once "internal/database.php";
include_once "internal/ui.php";
include_once "internal/staff_session.php";
?>

<!DOCTYPE html>
<html>
	<head>
		<?php
			$database = new Database();
			$board = $database->get_board($board_id);
			echo "<title>/$board->id/ - $board->title</title>";			
		
			include "internal/link_css.php";
		?>
	</head>

	<body>
		<?php
			include "topbar.php";
		?>

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
				(new PostForm("/internal/actions/post.php", "POST"))
					->add_text_field("Title", "title")
					->add_text_area("Comment", "comment")
					->add_file("File", "file")
					->add_hidden_data("board", "$board_id")
					->finalize();
			?>
		</div>

		<div id="posts">
			<?php
				function sort_func($o1, $o2)
				{
					return $o1->bump_time < $o2->bump_time;
				}

				usort($board->posts, "sort_func");

				foreach ($board->posts as $post)
				{
					echo "<hr>";
					$post->display($mod_mode, true);
				}
			?>	
		</div>

		
		<script src="/internal/board_index.js"></script>	
		<script src=/internal/post_display.js></script>

		<?php include "footer.php" ?>
	</body>
</html>

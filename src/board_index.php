<?php
include_once "internal/board.php";
include_once "internal/ui.php";
include_once "internal/staff_session.php";
include_once "internal/bans.php";

function show_pages()
{
	echo "<span class=board_pages>Pages: ";
	global $board;
	
	$page_count = $board->get_pages_count();

	for ($i = 0; $i < $page_count; $i++)
	{
		if ($i == $_GET["p"])
			echo "<a class=selected_page href='?p=$i'>[$i]</a>";
		else
			echo "<a href='?p=$i'>[$i]</a>";
	}

	echo "</span>";
}
?>

<!DOCTYPE html>
<html>
	<head>
		<?php
			$database = new Database();
			$board = board_get($board_id, $_GET["p"]);
			echo "<title>/$board->id/ - $board->title</title>";
		
			include "internal/link_css.php";
		?>
	</head>

	<body>
		<?php
			include "topbar.php";

			if (isset($_GET["error"]))
				echo "<script>alert('" . $_GET["error"] . "')</script>"
		?>

		<div class="title">
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
				if (staff_session_is_valid())
					echo "<p id=staff_disclaimer>Posting as staff</p>";

				(new PostForm("/internal/actions/post.php", "POST"))
					->add_text_field("Title", "title")
					->add_text_area("Comment", "comment")
					->add_file("File", "file")
					->add_hidden_data("board", "$board_id")
					->finalize();
			?>
		</div>

			<?php show_pages(); ?>

		<div id="posts">
			<?php
				$sticky_posts = array();
				$posts = array();

				foreach ($board->posts as $post)
				{
					if ($post->sticky)
						array_push($sticky_posts, $post);
					else
						array_push($posts, $post);
				}

				function sort_func($o1, $o2)
				{
					return $o1->bump_time < $o2->bump_time;
				}

				
				foreach ($sticky_posts as $post)
				{
					echo "<hr>";
					$post->display($mod_mode, true);
				}

				foreach ($posts as $post)
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

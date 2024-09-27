<?php
include_once "internal/database.php";
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
			<form action=/internal/actions/post.php method=POST enctype="multipart/form-data">
				<p>Title</p>
				<input type=text name=title>
				<p>Comment</p>
				<textarea name=comment></textarea>
				<p>File</p>
				<input type=file name=file id=file>
				<br>
				<button type=submit>Submit</button>
				
				<input type=hidden name=is_reply value=0>
				<?php echo "<input type=hidden name=board value=$board_id>" ?>
			</form>
		</div>

		<div id="posts">
			<?php
				foreach ($board->posts as $post)
					$post->display();
			?>	
		</div>
	</body>
</html>

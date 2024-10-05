<!DOCTYPE html>
<?php
	include_once "internal/board.php";
	include_once "internal/bans.php";

	if (is_file("first_run"))
	{
		header("Location: /first_setup.php");
		die();
	}
?>

<html>
	<head>
		<?php
		include "internal/chaninfo.php"; 
	
		$chan_info = chan_info_read();
		echo "<title>$chan_info->chan_name</title>";

		include "internal/link_css.php";
		?>
	</head>

	<body>
		<?php
			include "topbar.php";
		?>

		<?php
			echo "<h1 class=title>$chan_info->chan_name</h1>";
		?>

		<div class="list">
			<?php echo "<h3 class=\"list_title\">Welcome to $chan_info->chan_name</h3>" ?>
			<div class="list_content">
				<?php
					echo "<pre>$chan_info->welcome</pre>"
				?>
			</div>
		</div>

		<br>

		<div class=list>
			<h3 class=list_title>Boards</h3>
			<div class=list_content>
			<?php
				$boards = board_list();

				foreach ($boards as $board)
				{
					echo "<a href=$board->id/>$board->title</a><br>";
				}

			?>
			</div>
		</div>
		
		<br>

		<div class="list">
			<h3 class="list_title">Stats</h3>
			<div class="list_content">
				<?php
					function formatBytes($size, $precision = 2)
					{
						$base = log($size, 1024);
						$suffixes = array('', 'K', 'M', 'G', 'T');   
					
						return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)] . "B";
					}

					$database = new Database();

					$post_count = 0;

					foreach (board_list() as $board)
					{
						$result = $database->query("select count(*) from posts_$board->id");
						$post_count += intval($result->fetch_assoc()["count(*)"]);
					}

					echo "<p>$post_count posts</p>";

					$uploaded_files = scandir("uploads");
					$file_count = 0;
					$file_size = 0;

					foreach($uploaded_files as $file)
					{
						if (!str_contains($file, "-thumb") && !str_starts_with($file, "."))
						{
							$file_size += filesize("uploads/$file");
							$file_count++;
						}
					}

					$file_size = formatBytes($file_size, 0);

					echo "<p>$file_count uploaded files</p>";
					echo "<p>$file_size of content</p>";
				?>
			</div>
		</div>

		<?php include "footer.php"; ?>
	</body>
</html>

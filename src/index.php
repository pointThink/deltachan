<!DOCTYPE html>
<?php
	include_once "internal/boars.php";

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

		<?php include "footer.php"; ?>
	</body>
</html>

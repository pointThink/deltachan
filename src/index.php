<!DOCTYPE html>
<?php
	include_once "internal/database.php";

	if (is_file("first_run"))
	{
		header("Location: /first_setup.php");
		die();
	}

	$database = new Database();
?>

<html>
	<head>
		<title>Index</title>
		<?php include "internal/link_css.php"; ?>
	</head>

	<body>
		<?php
			include "topbar.php";
		?>

		<br>

		<div class=list>
			<h3 class=list_title>Boards</h3>
			<div class=list_content>
			<?php
				$boards = $database->get_boards();

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

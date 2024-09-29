<?php
include_once "../../database.php";
include_once "../../staff_session.php";

if (!staff_session_is_valid())
	die("You are not allowed here");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Manage boards</title>
		<?php include "../../link_css.php" ?>
	</head>

	<body>
		<?php include "../../../topbar.php" ?>
		
		<h1 class=title>Manage boards</h1>

		<table class=manage_table>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Subtitle</th>
				<th>Edit</th>
			</tr>

			<?php
				$database = new Database();
				$boards = $database->get_boards();

				foreach ($boards as $board)
				{
					echo "
					<tr>
						<td>$board->id</td>
						<td>$board->title</td>
						<td>$board->subtitle</td>
						<td><a href=#>Edit (TODO)</a></td>
					</tr>
					";
				}
			?>
		</table>

		<a class=manage_link href=/internal/actions/staff/add_board.php>Add board</a>

		<?php include "../../../footer.php" ?>
	</body>
</html>

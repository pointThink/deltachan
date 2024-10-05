<?php
include_once "../board.php";
include_once "../staff_session.php";

if (!staff_session_is_valid() || !staff_is_admin()) 
	die("You are not allowed here");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Manage boards</title>
		<?php include "../link_css.php" ?>
	</head>

	<body>
		<?php include "../../topbar.php" ?>
		
		<h1 class=title>Manage boards</h1>

		<table class=manage_table>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Subtitle</th>
				<th>Edit</th>
			</tr>

			<?php
				$boards = board_list();

				foreach ($boards as $board)
				{
					echo "
					<tr>
						<td>$board->id</td>
						<td>$board->title</td>
						<td>$board->subtitle</td>
						<td><a href=/internal/actions/staff/edit_board.php?id=$board->id>Edit</a></td>
					</tr>
					";
				}
			?>
		</table>

		<a class=manage_link href=/internal/actions/staff/add_board.php>Add board</a>

		<?php include "../../footer.php" ?>
	</body>
</html>

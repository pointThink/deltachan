<?php
	include_once "internal/staff_session.php";
	include_once "internal/database.php";

	if (!staff_session_is_valid())
	{
		header("Location: /staff_login.php");
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<?php include "internal/link_css.php"; ?>
	</head>

	<body>
		<?php include "topbar.php"; ?>

		<div id=staff_dashboard_title>
			<h1>Staff dashboard</h2>

			<?php
				$current_user = staff_get_current_user();
				echo "<h4>Logged in as $current_user->username</h4>";
				echo "<a href=/internal/actions/staff/logout.php>Log out</a>";	
			?>
		</div>

		<br>

		<div id=staff_dashboard_content>
			<?php
				$database = new Database();

				echo "<h4>Boards</h4>";
				echo "<ul>";

				foreach ($database->get_boards() as $b)
					echo "<li><a href=/$b->id/>/$b->id/ - $b->title</a></li>";

				echo "</ul><br>";
				

				if ($current_user->role == "admin")
				{
					echo "<h4>Admin actions</h4>
					<ul>
						<li><a href=/internal/actions/staff/chan_setup.php>Setup imageboard</a></li>
						<li><a href=/internal/actions/staff/manage_accounts.php>Manage accounts</a></li>
						<li><a href=/internal/actions/staff/manage_boards.php>Manage boards</a></li>
					</ul>";
				}
			?>

			<br>

			<h4>Moderator actions</h4>
			<ul>
				<li><a href=/internal/actions/staff/approve_posts.php>View unnaproved posts</a></li>
				<li><a href=/internal/actions/staff/view_reports.php>View reported posts</a></li>
				<li><a href=/internal/actions/staff/manage_bans.php>Manage bans</a></li>
			</ul>
		</div>

		<?php include "footer.php" ?>
	</body>
</html>

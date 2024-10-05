<?php
include_once "../bans.php";
include_once "../ui.php";
include_once "../staff_session.php";

if (!staff_session_is_valid() || !staff_is_moderator()) 
	die("You are not allowed here");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Manage bans</title>
		<?php include "../link_css.php" ?>
	</head>

	<body>
		<?php include "../../topbar.php" ?>
		
		<h1 class=title>Manage bans</h1>

		<table class=manage_table>
			<tr>
				<th>IP adress</th>
				<th>Ban reason</th>
				<th>Expire date</th>
				<th>Unban</th>
			</tr>

			<?php
				$bans = ban_list_banned_ips();

				foreach ($bans as $ip)
				{
                    $ban_info = ban_read($ip);

					echo "
					<tr>
						<td>$ip</td>
						<td>$ban_info->reason</td>
                    ";
                    
                    if ($ban_info->expires)
                    {
                        $end_date = strtotime($ban_info->date) + $ban_info->duration;
                        $end_date_format = date("d/m/y H:i", $end_date);
                        echo "<td>$end_date_format</td>";
                    }
                    else
                    {
                        echo "<td>Permanent</td>";
                    }

                    echo "<td>";
                    (new ActionLink("/internal/actions/staff/unban.php", "unban_$ip", "Unban"))
                        ->add_data("ip", $ip)
                        ->finalize();
                    echo "</td>";
				}
			?>
		</table>
		<?php include "../../footer.php" ?>
	</body>
</html>

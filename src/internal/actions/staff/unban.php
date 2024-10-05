<?php
include_once "../../bans.php";
include_once "../../staff_session.php";

if (!staff_session_is_valid() || !staff_is_moderator()) 
	die("You are not allowed here");

ban_remove($_POST["ip"]);

header("Location: /internal/staff_forms/manage_bans.php");
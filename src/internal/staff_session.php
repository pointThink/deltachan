<?php
session_start();

include_once "database.php";

$database = new Database();

class StaffAccountInfo
{
	public $username;
	public $password_hash;
	public $role;
	public $contact_email;
}

enum LoginResult
{
	case SUCCESS;
	case FAILED_INVALID_USER;
	case FAILED_INVALID_PASSWORD;
}

function staff_login($username, $password_hash)
{
	global $database;
	$account = $database->read_staff_account($username);

	if ($account == NULL)
		return LoginResult::FAILED_INVALID_USER;

	if ($password_hash != $account->password_hash)
		return LoginResult::FAILED_INVALID_PASSWORD;

	$_SESSION["staff_username"] = $username;
	$_SESSION["staff_password_hash"] = $password_hash;
	
	return LoginResult::SUCCESS;
}

function staff_session_is_valid()
{
	global $database;
	if (!isset($_SESSION["staff_username"]) || !isset($_SESSION["staff_password_hash"]))
		return false;
	
	$current_user = $database->read_staff_account($_SESSION["staff_username"]);

	if ($current_user == NULL)
		return false;

	if ($current_user->password_hash != $_SESSION["staff_password_hash"])
		return false;

	return true;
}

function staff_session_logout()
{
	unset($_SESSION["staff_username"]);
	unset($_SESSION["staff_password_hash"]);
}

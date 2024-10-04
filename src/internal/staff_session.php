<?php
session_start();

include_once "database.php";

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
	$database = new Database();
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
	$database = new Database();
	if (!isset($_SESSION["staff_username"]) || !isset($_SESSION["staff_password_hash"]))
	{
		staff_logout();
		return false;
	}
	
	$current_user = $database->read_staff_account($_SESSION["staff_username"]);

	if ($current_user == NULL)
	{
		staff_logout();
		return false;
	}

	if ($current_user->password_hash != $_SESSION["staff_password_hash"])
	{
		staff_logout();
		return false;
	}

	return true;
}

function staff_logout()
{
	unset($_SESSION["staff_username"]);
	unset($_SESSION["staff_password_hash"]);
}

function staff_get_current_user()
{
	$database = new Database();
	return $database->read_staff_account($_SESSION["staff_username"]);
}

function staff_is_admin()
{
	return staff_get_current_user()->role == "admin";
}

<?php
class Database
{
	private $mysql_connection;

	public function __construct($db_host, $username, $password)
	{
		$this->$mysql_connection = new mysqli($db_host, $username, $password);

		if ($this->$mysql_connection->connection_error)
			die("Connection failed: $database->connection_error");
	}

	// Sets up a database with necesary tables for a board 
	public function setup_board_database($board_id)
	{
		$this->query("create database if not exists $board_id;");

		$this->$my_sql_connection->select_db("$board_id");

		$this->query("
			create table if not exists posts (
				id int not null auto_increment primary key,
				is_reply int not null,
				replies_to int,

				title varchar(255),
				post_body text,
				image_file_name varchar(255),

				poster_ip varchar(255) not null,
				poster_country varchar(2),

				is_staff_post int not null,
				staff_username varchar(255)
			);
		");
	}

	private function query($str)
	{
		return $this->$mysql_connection->query($str);
	}
}

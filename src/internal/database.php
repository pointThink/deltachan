<?php
include_once "post.php";

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

				creation_time datetime not null default current_timestamp,
				bump_time datetime not null default current_timestamp,

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

	public function setup_board_info_database()
	{
		$this->query("create database if not exists board_info;");
		
		$this->$mysql_connection->select_db("board_info");

		$this->query("
			create table if not exists board_info (
				id varchar(255) not null primary key,
				title varchar(255) not null,
				subtitle varchar(255)
			);
		");
	}

	public function add_board_info_row($id, $title, $subtitle)
	{
		$this->$mysql_connection->select_db("board_info");
		$this->query("
			insert into board_info (
				id, title, subtitle
			) values (
				'$id', '$title', '$subtitle'
			);
		");
	}

	// Adds a post entry to the posts table
	// Does not upload any attachments!
	public function write_post($board_id, $is_reply, $replies_to, $title, $body, $image_file, $poster_ip, $poster_country, $is_staff_post, $staff_username)
	{
		$this->$mysql_connection->select_db($board_id);

		if (!$is_reply) $replies_to = 0;
		if (!$is_staff_post) $staff_username = "";

		$is_reply = intval($is_reply);

		// prevent sql injection
		$title = $this->$mysql_connection->real_escape_string($title);
		$body = $this->$mysql_connection->real_escape_string($body);	

		$query = "insert into posts(
			is_reply, replies_to, title, post_body, image_file_name, poster_ip, poster_country, is_staff_post, staff_username
		) values (
			$is_reply, $replies_to, '$title', '$body', '$image_file', '$poster_ip', '$poster_country', $is_staff_post, '$staff_username'
		);";

		echo $query;

		$this->query($query);
	}

	// returns a post object
	public function read_post($board, $id)
	{
		$post = new Post();

		$this->$mysql_connection->select_db($board);
		$query_result = $this->query("select * from posts where id = $id;");

		if ($query_result->num_rows <= 0)
			return null;

		$post_array = $query_result->fetch_array();

		$post->board = $board;
		$post->id = $id;
		$post->is_reply = $post_array["is_reply"];
		$post->replies_to = $post_array["replies_to"];
		
		$post->creation_time = $post_array["creation_time"];
		$post->bump_time = $post_array["bump_time"];

		$post->body = $post_array["post_body"];
		$post->title = $post_array["title"];
		$post->image_file = $post_array["image_file_name"];

		$post->poster_ip = $post_array["poster_ip"];
		$post->poster_country = $post_array["poster_country"];

		$post->is_staff_post = $post_array["is_staff_post"];
		$post->staff_username = $post_array["staff_username"];

		return $post;
	}

	private function query($str)
	{
		return $this->$mysql_connection->query($str);
	}
}

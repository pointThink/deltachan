<?php
include_once __DIR__ . "/post.php";
include_once __DIR__ . "/board.php";
include_once __DIR__ . "/staff_session.php";

class Database
{
	private $mysql_connection;

	public function __construct()
	{
		$this->$mysql_connection = new mysqli("localhost", "root", "root");

		if ($this->$mysql_connection->connection_error)
			die("Connection failed: $this->$mysql_connection->connection_error");
	}

	// Sets up a database with necesary tables for a board 
	public function setup_board_database($board_id)
	{

		$this->$my_sql_connection->select_db("deltachan");

		$this->query("
			create table if not exists posts_$board_id (
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
				staff_username varchar(255),

				approved int not null default 0
			);
		");
	}

	public function setup_meta_info_database()
	{
		$this->query("create database if not exists deltachan;");
		
		$this->$mysql_connection->select_db("deltachan");

		$this->query("
			create table if not exists board_info (
				id varchar(255) not null primary key,
				title varchar(255) not null,
				subtitle varchar(255)
			);
		");

		$this->query("
			create table if not exists staff_accounts (
				username varchar(30) not null primary key,
				password_hash varchar(128) not null,
				role varchar(128) not null,
				contact_email varchar(128)
			);
		");
	}

	public function add_board_info_row($id, $title, $subtitle)
	{
		$this->$mysql_connection->select_db("deltachan");
		$this->query("
			insert into board_info (
				id, title, subtitle
			) values (
				'$id', '$title', '$subtitle'
			);
		");
	}

	// Fetches all the boards on the chan
	public function get_boards()
	{
		$this->$mysql_connection->select_db("deltachan");
		$query_result = $this->query("select * from board_info;");
		$boards = array();

		while ($board_array = $query_result->fetch_assoc())
		{
			$board = new Board();
			$board->id = $board_array["id"];
			$board->title = $board_array["title"];
			$board->subtitle = $board_array["subtitle"];
			array_push($boards, $board);
		}

		return $boards;
	}

	public function get_board($board_id)
	{
		$this->$mysql_connection->select_db("deltachan");
		$query_result = $this->query("select * from board_info where id = '$board_id';");
		$board_array = $query_result->fetch_array();
		$board = new Board();

		$board->id = $board_array["id"];
		$board->title = $board_array["title"];
		$board->subtitle = $board_array["subtitle"];

		$query_result = $this->query("select id from posts_$board_id where is_reply = 0;");
		
		while ($post_id = $query_result->fetch_assoc())
		{
			array_push($board->posts, $this->read_post($board_id, $post_id["id"]));
		}

		return $board;
	}

	// Adds a post entry to the posts table
	// Does not upload any attachments!
	public function write_post($board_id, $is_reply, $replies_to, $title, $body, $poster_ip, $poster_country, $is_staff_post, $staff_username)
	{
		$this->$mysql_connection->select_db("deltachan");

		if (!$is_reply) $replies_to = 0;
		if (!$is_staff_post) $staff_username = "";

		$is_reply = intval($is_reply);

		// prevent sql injection
		$title = $this->$mysql_connection->real_escape_string($title);
		$body = $this->$mysql_connection->real_escape_string($body);	

		$query = "insert into posts_$board_id(
			is_reply, replies_to, title, post_body, poster_ip, poster_country, is_staff_post, staff_username, approved
		) values (
			$is_reply, $replies_to, '$title', '$body', '$poster_ip', '$poster_country', $is_staff_post, '$staff_username', 0
		);";

		$query_result = $this->query($query);

		// return the newly created post
		return $this->read_post($board_id, $this->$mysqli_connection->insert_id);
	}

	public function bump_post($board, $id)
	{
		$this->$mysql_connection->select_db("deltachan");	
		$this->query("
			update posts_$board
			set bump_time = current_timestamp
			where id = $id;
		");
	}

	public function update_post_file($board, $id, $file)
	{
		$this->$mysql_connection->select_db("deltachan");
		$file = $this->$mysqli_connection->real_escape_string($file);

		$this->query("
			update posts_$board
			set image_file_name = '$file'
			where id = $id;
		");
	}

	// returns a post object
	public function read_post($board, $id)
	{
		$post = new Post();

		$this->$mysql_connection->select_db("deltachan");
		$query_result = $this->query("select * from posts_$board where id = $id;");

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

		if (!$post_array["is_reply"])
			$post->replies = $this->get_post_replies($board, $id);

		return $post;
	}

	public function remove_post($board, $id)
	{
		$this->$mysqli_connection->select_db("deltachan");

		$this->query("
			delete from posts_$board where id = $id;
		");
	}

	public function get_post_replies($board, $post)
	{
		$replies = array();
		$this->$mysql_connection->select_db("deltachan");
		$id_str = strval($post);

		$result = $this->query("
			select id from posts_$board where is_reply = 1 and replies_to = $id_str;
		");

		while ($reply = $result->fetch_assoc())
			array_push($replies, $this->read_post($board, $reply["id"]));

		return $replies;
	}

	public function write_staff_account($username, $password_hash, $role, $contact_email = "")
	{
		$this->$mysql_connection->select_db("deltachan");

		$this->query("
			insert into staff_accounts (
				username, password_hash, role, contact_email
			) values (
				'$username', '$password_hash', '$role', '$contact_email'
			);
		");
	}

	public function read_staff_account($username)
	{
		$this->$mysql_connection->select_db("deltachan");

		$account_info = new StaffAccountInfo();
		$username = $this->$mysqli_connection->real_escape_string($username);

		$result = $this->query("
			select * from staff_accounts where username='$username'
		");

		
		if ($result->num_rows <= 0)
			return NULL;

		$account_array = $result->fetch_array();

		$account_info->username = $username;
		$account_info->password_hash = $account_array["password_hash"];
		$account_info->role = $account_array["role"];
		$account_info->contact_email = $account_array["contact_email"];

		return $account_info;
	}

	private function query($str)
	{
		return $this->$mysql_connection->query($str);
	}
}

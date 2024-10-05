<?php
include_once __DIR__ . "/post.php";
include_once __DIR__ . "/board.php";
include_once __DIR__ . "/staff_session.php";
include_once __DIR__ . "/config.php";

class Database
{
	public $mysql_connection;

	public function __construct($host = "", $user = "", $password = "")
	{
		global $deltachan_config;
		$key = file_get_contents($deltachan_config["crypt_key_path"]);

		if ($host == "" || $user == "" || $password == "")
		{
			// decrypt credentials
			$host = openssl_decrypt($deltachan_config["database_host"], "aes-256-ecb", $key);
			$user = openssl_decrypt($deltachan_config["database_user"], "aes-256-ecb", $key);
			$password = openssl_decrypt($deltachan_config["database_password"], "aes-256-ecb", $key);
		}

		try
		{
			$this->mysql_connection = new mysqli($host, $user, $password);
		}
		catch (Exception $e)
		{
			echo "DB connection error: $e";
		}
	}

	public function setup_meta_info_database()
	{
		$this->query("create database if not exists deltachan;");
		
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

		$this->query("
			create table if not exists bans (
				ip varchar(255) not null primary key,
				reason text not null,
				date datetime not null default current_timestamp,
				duration int not null default 0
			);
		");
	}

	// Adds a post entry to the posts table
	// Does not upload any attachments!
	public function write_post($board_id, $is_reply, $replies_to, $title, $body, $poster_ip, $poster_country, $is_staff_post, $staff_username)
	{
		if (!$is_reply) $replies_to = 0;
		if (!$is_staff_post) $staff_username = "";

		$is_reply = intval($is_reply);

		// prevent sql injection
		$title = $this->mysql_connection->real_escape_string($title);
		$body = $this->mysql_connection->real_escape_string($body);	

		$query = "insert into posts_$board_id(
			is_reply, replies_to, title, post_body, poster_ip, poster_country, is_staff_post, staff_username, approved
		) values (
			$is_reply, $replies_to, '$title', '$body', '$poster_ip', '$poster_country', $is_staff_post, '$staff_username', 0
		);";

		$query_result = $this->query($query);

		// return the newly created post
		return $this->read_post($board_id, $this->mysql_connection->insert_id);
	}

	public function bump_post($board, $id)
	{
		$this->query("
			update posts_$board
			set bump_time = current_timestamp
			where id = $id;
		");
	}

	public function update_post_file($board, $id, $file)
	{
		$file = $this->mysql_connection->real_escape_string($file);

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
		$this->query("
			delete from posts_$board where id = $id;
		");
	}

	public function get_post_replies($board, $post)
	{
		$replies = array();
		$this->mysql_connection->select_db("deltachan");
		$id_str = strval($post);

		$result = $this->query("
			select id from posts_$board where is_reply = 1 and replies_to = $id_str;
		");

		while ($reply = $result->fetch_assoc())
			array_push($replies, $this->read_post($board, $reply["id"]));

		return $replies;
	}

	public function query($str)
	{
		$this->mysql_connection->select_db("deltachan");
		return $this->mysql_connection->query($str);
	}
}

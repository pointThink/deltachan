<?php

class Post
{
	public $id;
	public $board;
	public $is_reply;
	public $replies_to;
	
	public $creation_time;
	public $bump_time;

	public $title;
	public $body;
	public $image_file;

	public $poster_ip;
	public $poster_country;

	public $is_staff_post;
	public $staff_username;

	public function display()
	{
		echo "$this->id | $this->board<br>";
		echo "$this->creation_time | $this->last_bump_time<br>";
		echo "$this->title<br>";
		echo "$this->body | $this->image_file<br>";
		echo "$this->is_staff_post | $this->staff_username<br><hr>";
	}
}

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
		echo "<div class=post>";
		echo "<a href=/$this->image_file><img class=post_attachment src='/$this->image_file'></a>";
		echo "<a href=/$this->board/post.php?id=$this->id><p class=post_id>>$this->id | $this->creation_time</p></a>";
		echo "<h4>$this->title</h4>";
		echo "<pre>$this->body</pre>";
		echo "</div>";
	}
}

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

	public $replies = array();

	public function format_and_show_text($text)
	{
		$text = htmlspecialchars($text);

		$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);

		$ret = ' ' . $text;
	
		// Replace Links with http://
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $ret);
	
		// Replace Links without http://
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>", $ret);

		// Replace Email Addresses
		$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
		$ret = substr($ret, 1);

		// bold
    	$ret = preg_replace('/\*\*(.+)\*\*/sU', '<b>$1</b>', $ret);
    	// italic
	    $ret = preg_replace('/\*(.+)\*/sU', '<i>$1</i>', $ret);

		$textParts = explode("\n", $ret);

		echo "<div class=post_text>\n";
    	foreach ($textParts as $part)
    	{
        	if (str_starts_with($part, "&gt"))
            	echo "<pre class='greentext'>$part</pre>";
        	else if (str_starts_with($part, "&lt"))
            	echo "<pre class='orangetext'>$part</pre>";
        	else if (str_starts_with($part, "^"))
            	echo "<pre class='bluetext'>$part</pre>";
        	else
            	echo "<pre>$part</pre>";
    	}
    	echo "</div>\n";
	}

	public function display()
	{
		echo "<div class=post>";

		if ($this->image_file != "")
			echo "<a href=/$this->image_file><img class=post_attachment src='/$this->image_file'></a>";
		
		echo "<a href=/$this->board/post.php?id=$this->id><p class=post_id>>$this->id | $this->creation_time</p></a>";
		echo "<h4>$this->title</h4>";
		$this->format_and_show_text($this->body);

		foreach ($this->replies as $reply)
			$reply->display_reply();

		echo "</div>";
	}

	public function display_reply()
	{
		echo "<div class=reply>";

		if ($this->image_file != "")
			echo "<a href=/$this->image_file><img class=post_attachment src='/$this->image_file'></a>";
		
		echo "<p class=post_id>>$this->id | $this->creation_time</p>";
		echo "<h4>$this->title</h4>";
		$this->format_and_show_text($this->body);
		echo "</div>";
	}
}

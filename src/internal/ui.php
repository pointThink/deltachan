<?php
class PostForm
{
	private $buffer = "";
	private $hidden_data = array();

	public function __construct($form_action, $action_method)
	{
		$this->buffer .= "<form action='$form_action' method=$action_method enctype=multipart/form-data>";
		$this->buffer .= "<table>";
	}

	public function add_text_field($label, $name, $value = "")
	{
		$this->buffer .= "<tr>";
		$this->buffer .= "<th>$label</th>";
		$this->buffer .= "<td><input type=text name=$name value='$value'></td>";
		$this->buffer .= "</tr>";

		return $this;
	}

	public function add_password_field($label, $name, $value = "")
	{
		$this->buffer .= "<tr>";
		$this->buffer .= "<th>$label</th>";
		$this->buffer .= "<td><input type=password name=$name value='$value'></td>";
		$this->buffer .= "</tr>";

		return $this;
	}

	public function add_text_area($label, $name, $value="")
	{
		$this->buffer .= "<tr>";
		$this->buffer .= "<th>$label</th>";
		$this->buffer .= "<td><textarea name=$name>$value</textarea>";
		$this->buffer .= "</tr>";

		return $this;
	}

	public function add_file($label, $name)
	{
		$this->buffer .= "<tr>";
		$this->buffer .= "<th>$label</th>";
		$this->buffer .= "<td><input type=file name=$name></td>";
		$this->buffer .= "</tr>";

		return $this;
	}

	public function add_hidden_data($name, $value)
	{
		$this->hidden_data[$name] = $value;
		return $this;
	}

	public function finalize()
	{
		$this->buffer .= "</table>";
		$this->buffer .= "<button type=submit>Submit</button>";
	
		// add hidden data to the form
		foreach ($this->hidden_data as $key => $value)
		{
			$this->buffer .= "<input type=hidden name=$key value='$value'>";
		}

		$this->buffer .= "</form>";
		echo $this->buffer;
	}
}

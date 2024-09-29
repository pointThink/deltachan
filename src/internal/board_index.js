function hide_replies(id)
{
	element = document.getElementById("replies_" + id);
	link = document.getElementById("hide_replies_" + id);

	if (element.style.display != "none")
	{
		link.innerHTML = "Show replies";
		element.style.display = "none";
	}
	else
	{
		link.innerHTML = "Hide replies"
		element.style.display = "block";
	}
}

console.log("Loaded?");

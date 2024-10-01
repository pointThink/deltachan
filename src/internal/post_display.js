function scroll_to_post(id)
{
    post = document.getElementById("post_" + id);
    post.scrollIntoView();
}

function clear_file_upload()
{
    file = document.querySelector(".file_upload");
    file.value = null;
}

<?php
include_once "database.php";

class Ban
{
    public $reason;
    public $expires;
    public $date;
    public $duration;
}

function ban_read($ip)
{
    $database = new Database();
    $query_result = $database->query("select * from bans where ip = '$ip';");

    if ($query_result->num_rows <= 0)
        return null;

    $ban_array = $query_result->fetch_array();

    $ban = new Ban();
    $ban->reason = $ban_array["reason"];
    $ban->expires = $ban_array["duration"] != 0;
    $ban->date = $ban_array["date"];
    $ban->duration = $ban_array["duration"];

    return $ban;
}

// duration in seconds
// durration = 0 means ban is permanent
function create_ban($ip, $reason, $duration)
{
    $database = new Database();
    
    $database->query("
        insert into bans (
            ip, reason, duration
        ) values (
            '$ip', '$reason', $duration
        );
    ");
}
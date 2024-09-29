<?php
include_once "../../staff_session.php";
staff_logout();
header("Location: " . $_SERVER["HTTP_REFERER"]);

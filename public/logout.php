<?php
require_once "../src/functions.php";

$_SESSION = [];
session_destroy();

header("Location: login.php");
exit;

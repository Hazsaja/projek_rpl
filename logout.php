<?php
require_once __DIR__ . '/config/session.php';

start_session_once();
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>

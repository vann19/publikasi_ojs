<?php
session_start();
session_destroy();
header("Location: /login-secure-xyz.php");
exit;

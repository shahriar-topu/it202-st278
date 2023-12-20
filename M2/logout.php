<?php
session_start();
//$_SESSION['logout_message'] = 'You have been logged out';
session_unset();
session_destroy();
header("Location: login.php?logout=true");
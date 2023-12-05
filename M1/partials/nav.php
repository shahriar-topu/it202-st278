<?php
//Note: this is to resolve cookie issues with port numbers
$domain = $_SERVER["HTTP_HOST"];
if (strpos($domain, ":")) {
    $domain = explode(":", $domain)[0];
}
$localWorks = true; //some people have issues with localhost for the cookie params
//if you're one of those people make this false

//this is an extra condition added to "resolve" the localhost issue for the session cookie

require_once(__DIR__ . "/../lib/functions.php");
session_start();
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    echo "<p>Successfully logged out</p>";
}
?> 
<link rel="stylesheet" href="<?php echo('styles.css'); ?>">
<nav>
    <ul>
        <?php if (is_logged_in()) : ?>
            <li><a href="home.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>

        <?php endif; ?>
        <?php if (!is_logged_in()) : ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
        <?php if (has_role('Admin')) : ?>
            <li><a href="create_role.php">Create Role</a></li>
            <li><a href="list_roles.php">List Roles</a></li>
        <?php endif; ?>
        <?php if (is_logged_in()) : ?>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>
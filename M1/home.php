<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        /* Paste the provided CSS code here */
    </style>
</head>
<body>
    <?php
    require(__DIR__ . "/partials/nav.php");
    ?>
    <div style="text-align: center; margin-top: 20px;">
        <h1>Home</h1>
        <?php
        //var_export($_SESSION);
        if (is_logged_in()) {
            echo "Welcome, " . get_user_email();
        } else {
            echo "You're not logged in";
        }
        //shows session info
        echo "<pre>" . var_export($_SESSION, true) . "</pre>";
        echo has_role('Admin');
        ?>
    </div>
</body>
</html>

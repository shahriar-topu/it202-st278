<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
        }

        section {
            text-align: center;
            margin-top: 20px;
        }

        h1 {
            color: #000;
        }

        .welcome-message {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #555;
        }

        .session-info {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: auto;
            max-width: 40%;
            margin: 0 auto;
            color: black; /* Set the text color to black */
        }

        .admin-info {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <header>
        <!-- No header content -->
    </header>
    
    <nav>
        <?php require(__DIR__ . "/partials/nav.php"); ?>
    </nav>

    <section>
        <h1>Home</h1>
        <div class="welcome-message">
            <?php
            if (is_logged_in()) {
                echo "Welcome, " . get_user_email();
            } else {
                echo "You're not logged in";
            }
            ?>
        </div>

        <div class="session-info">
            <?php
            // Display session information
            echo "<pre>" . var_export($_SESSION, true) . "</pre>";
            
            // Check if the user has the 'Admin' role
            echo has_role('Admin') ? "<p class='admin-info'>You have Admin privileges</p>" : "<p style='color: black;'>You don't have Admin privileges</p>";
            ?>
        </div>
    </section>
</body>
</html>

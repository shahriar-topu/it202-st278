<?php
require(__DIR__ . "/partials/nav.php");
?>

<form onsubmit="return validate(this)" method="POST">
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" required />
    </div>
    <div>
        <label for="pw">Password</label>
        <input type="password" id="pw" name="password" required minlength="8" />
    </div>
    <input type="submit" value="Login" />
</form>
<script>
    function validate(form) {
        // TODO 1: implement JavaScript validation
        // ensure it returns false for an error and true for success

        return true;
    }
</script>
<?php

//TODO 2: add PHP Code
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    //session_start();
    

    //TODO 3
    $hasError = false;
    if (empty($email)) {
        echo "<script>alert('Email must not be empty');</script>";
        $hasError = true;
    }
    //sanitize
    $email = sanitize_email($email);
    //validate
    if (!is_valid_email($email)) {
        echo "<script>alert('Invalid email address');</script>";
        $hasError = true;
    }
    if (empty($password)) {
        echo "<script>alert('Password must not be empty');</script>";
        $hasError = true;
    }
    if (strlen($password) < 8) {
        echo "<script>alert('Password too short');</script>";
        $hasError = true;
    }
    if (!$hasError) {
        //TODO 4
        $db = getDB();
        $stmt = $db->prepare("SELECT id, email, password from Users where email = :email");
        try {
            $r = $stmt->execute([":email" => $email]);
            if ($r) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $hash = $user["password"];
                    unset($user["password"]);
                    if (password_verify($password, $hash)) {
                        echo "<script>alert('Welcome $email');</script>";
                        $_SESSION["user"] = $user;
                        try {
                            //lookup potential roles
                            $stmt = $db->prepare("SELECT Roles.name FROM Roles 
                        JOIN UserRoles on Roles.id = UserRoles.role_id 
                        where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");
                            $stmt->execute([":user_id" => $user["id"]]);
                            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch all since we'll want multiple
                        } catch (Exception $e) {
                            error_log(var_export($e, true));
                        }
                        //save roles or empty array
                        if (isset($roles)) {
                            $_SESSION["user"]["roles"] = $roles; //at least 1 role
                        } else {
                            $_SESSION["user"]["roles"] = []; //no roles
                        }
                        echo "<script>location.href='home.php';</script>";
                    } else {
                        echo "<script>alert('Invalid password');</script>";
                    }
                } else {
                    echo "<script>alert('Email not found');</script>";
                }
            }
        } catch (Exception $e) {
            echo "<script>alert('An error occurred');</script>";
        }
    }
}
?>
<style>
    nav {
        background-color: #333333;
        padding: 10px;
        text-align: center;
    }

    nav a {
        color: black;
        text-decoration: none;
        margin: 10px 10px;
        font-weight: bold;
    }

    nav a:hover {
        text-decoration: underline;
    }
</style>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    form {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #333333;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
        border: 1px solid #cccccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: black;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        margin-bottom: 10px;
    }
</style>

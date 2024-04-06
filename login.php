<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <?php
        // Include header
        include "./header.php";

        // Check if user is already logged in
        if (isset($_SESSION["id"]) && isset($_SESSION["name"])) {
            // Redirect logged-in users to homepage
            header("Location: ./");
            exit();
        }

        //Include database connection
        include "./includes/db_connection.php";

        // Variable to store the error messages
        $errorMessage = "";

        //  Check if form is submitted and the "login" button is clicked
        if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
            $password = $_POST["password"];

            // Validate email format
            if ($email === false) {
                $errorMessage = "Invalid email format";
            } else {
                // Fetch user data
                $sql = "SELECT id, name, password FROM users WHERE email = ?";

                // Prepare the statement
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bind_param("s", $email);

                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                // Check if user found
                if ($result->num_rows == 1) {
                    $user_row = $result->fetch_assoc();

                    // Check password
                    if(password_verify($password, $user_row["password"])) {
                        
                        // Set session variable
                        $_SESSION["id"] = $user_row["id"];
                        $_SESSION["name"] = $user_row["name"];

                        // Redirect to homepage after successful login
                        header("Location: ./");
                        exit();
                    } else {
                        $errorMessage = "Incorrect password";
                    }
                } else {
                    $errorMessage = "User not found";
                }
            }
        }  
    ?>

    <main>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <?php if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])): ?>
                <p><?php echo $errorMessage; ?></p>
            <?php endif; ?>

            <button type="submit" name="login">Login</button>
        </form>
    </main>
</body>
</html>
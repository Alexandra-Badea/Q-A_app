<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="./css/style.css" />
    </head>
    <body>
        <?php
            // Check if user is already logged in
            if (isset($_SESSION["id"]) && isset($_SESSION["name"])) {
                // Redirect logged-in users to homepage
                header("Location: ./");
                exit();
            }

            // Include header
            include "./includes/header.php";

            // Include database connection
            include "./includes/db_connection.php";

            //  Check if form is submitted and the "register_user" button is clicked
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register_user"])) {

                // Array to store error messages
                $errorMessages = array(
                    "name" => "",
                    "email" => "",
                    "password" => "",
                    "confirm_password" => ""
                );

                // Declare variable for success message
                $successMessage = "";

                // Retrive form data
                $name = test_input($_POST["name"]);
                $email = test_input($_POST["email"]);
                $password = $_POST["password"];
                $confirmPassword = $_POST["confirm_password"];

                // Validate form data
                if (empty($name)) {
                    $errorMessages["name"] = "Name is required";
                    echo "Name is required";
                } 
                
                if (empty($email)) {
                    $errorMessages["email"] = "Email is required";
                    echo "Email is required";
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errorMessages["email"] = "Invalid email format";
                    echo "Invalid email format";
                } 
                
                if (empty($password)) {
                    $errorMessages["password"] = "Password required";
                } else if (strlen($password) < 8) {
                    $errorMessages["password"] = "Password should be at least 8 characters long";
                } else if (!preg_match('/[A-Z]/', $password)) {
                    $errorMessages["password"] = "You should have at least one capital letter";
                } else if (!preg_match('/[0-9]/', $password)) {
                    $errorMessages["password"] = "You should have at least one digit";
                } 
                
                if (empty($confirmPassword)) {
                    $errorMessages["confirm_password"] = "Password required";
                } else if ($confirmPassword !== $password) {
                    $errorMessages["confirm_password"] = "Passwords do not match";
                } 
                
                if (empty(array_filter($errorMessages))) {
                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Insert user data into the database
                    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            
                    // Prepare the statement
                    $stmt = $conn->prepare($sql);
            
                    // Bind parameters
                    $stmt->bind_param("sss", $name, $email, $hashedPassword);
            
                    // Execute the statement
                    if ($stmt->execute()) {
                        $successMessage = "New user created successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                    
                    // Close the statement
                    $stmt->close();
                } 
            }

            // Function to validate input form
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
        ?>

        <main>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" require>
                <p><?php echo isset($errorMessages["name"]) ? $errorMessages["name"] : "" ?></p>
            
                <label for="email">Email</label>
                <input type="text" id="email" name="email" require>
                <p><?php echo isset($errorMessages["email"]) ? $errorMessages["email"] : "" ?></p>
            
                <label for="password">Password</label>
                <input type="password" id="password" name="password" require>
                <p><?php echo isset($errorMessages["password"]) ? $errorMessages["password"] : "" ?></p>
            
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" require>
                
                <p><?php echo isset($errorMessages["confirm_password"]) ? $errorMessages["confirm_password"] : "" ?></p>
                <p><?php echo isset($successMessage) ? $successMessage : "" ?></p>

                <button type="submit" name="register_user">Register</button>
            </form>
        </main>
    </body>
</html>
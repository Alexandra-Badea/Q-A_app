<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <?php
        // Include header
        include "./header.php";

        // Check if user is already logged in
        if (!isset($_SESSION["id"]) && !isset($_SESSION["name"])) {
            // Redirect logged off user to homepage
            header("Location: ./");
            exit();
        }

        // Include database connection
        include './includes/db_connection.php';

        // Array to store error messages
        $errorMessages = array(
            "title" => "",
            "question" => ""
        );

        // Declare variable for success message
        $successMessage = "";

        //  Check if form is submitted and the "add_task" button is clicked
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_task"])) {
            
            // Retrive form data
            $title = $_POST["title"];
            $question = $_POST["question"];

            // Get user_id from session
            $userId = $_SESSION["id"];

            // Validate form data
            if (empty($title)) {
                $errorMessages["title"] = "Title is required";
            }
            
            if (empty($question)) {
                $errorMessages["question"] = "No question provided";
            }

            if (empty(array_filter($errorMessages))) {
                // Insert task data into the database
                $sql = "INSERT INTO tasks (user_id, title, question) VALUES (?, ?, ?)";

                // Prepare the statement
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bind_param('iss', $userId, $title, $question);

                // Execute the statement
                if ($stmt->execute()) {
                    $successMessage = "New record created successfully.";
                } else {
                    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
                }

                // Close the statement
                $stmt->close();
            }
        }
    ?>

    <main>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="title">Title</label>
            <input type=text id="title" name="title" />
            <p><?php echo isset($errorMessages["title"]) ? $errorMessages["title"] : "" ?></p>

            <label for="question">Question</label>
            <textarea id="question" name="question"></textarea>
            
            <p><?php echo isset($errorMessages["question"]) ? $errorMessages["question"] : "" ?></p>
            <p><?php echo isset($successMessage) ? $successMessage : "" ?></p>
            
            <button type="submit" name="add_task">Publish</button>
        </form>
    </main>
</body>
</html>
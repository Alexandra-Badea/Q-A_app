
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <?php
        // Include header
        include "./includes/header.php";
    ?>

    <main>
        <?php

            // Include database connection
            include "./includes/db_connection.php";

            // Declare error message variable
            $errorMessage = "";

            // Declare variable for success message
            $successMessage = "";

            // Check if task_id is provided in the URL
            if (isset($_GET["task_id"])) {
                $taskId = $_GET["task_id"];

                // Fetch the task data
                $sql = "SELECT title, question FROM tasks WHERE id = ?";

                // Prepare the statement
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bind_param('i', $taskId);

                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                // Check if there are rows returned
                if ($result->num_rows > 0) {
                    $task_row = $result->fetch_assoc();
                    $title = $task_row["title"];
                    $question = $task_row["question"];
                    echo "<h2>$title</h2>";
                    echo "<p>$question</p>";
                } else {
                    echo "<p>Task not found.</p>";
                }

                // Close statement
                $stmt->close();

                // Insert answer data into the database
                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["send_answer"])) {
                    // Retrive form data
                    $answer = $_POST["answer"];
            
                    // Get user_id from session
                    $userId = $_SESSION["id"];
                    
                    // Validate form data
                    if (empty($answer)) {
                        $errorMessage = "Answer cannot be empty";
                    }
            
                    if (empty($errorMessage)) {
                        // Insert answer into the database
                        $sql = "INSERT INTO answers (user_id, task_id, answer) VALUES (?, ?, ?)";
            
                        // Prepare the statement
                        $stmt = $conn->prepare($sql);
            
                        // Bind parameters
                        $stmt->bind_param("iis", $userId, $taskId, $answer);
            
                        // Execute the statement
                        if ($stmt->execute()) {
                            $successMessage = "New record created successfully.";
                        } else {
                            echo "<p>Error: " . $sql . "<br>" . $conn->error . ".</p>";
                        }

                        // Close statement
                        $stmt->close();
                    }
                }
            ?>

            <?php if (isset($_SESSION["id"]) && isset($_SESSION["name"])) { ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?task_id=' . urldecode($_GET["task_id"]); ?>" method="POST">           
                    <label for="answer">Your answer:</label>
                    <textarea name="answer" id="answer" cols="30" rows="10"></textarea>
                    
                    <p><?php echo isset($errorMessage) ? $errorMessage : ""?></p>
                    <p><?php echo isset($successMessage) ? $successMessage : "" ?></p>

                    <button type="submit" name="send_answer">Post</button>
                </form>
            <?php } ?> 

            <?php
                // Fetch the answer data
                $sql = "SELECT users.name, answers.answer, answers.user_id, DATE(answers.reg_date) AS date_only FROM answers INNER JOIN users ON answers.user_id = users.id WHERE answers.task_id = ?";

                // Prepare the statement
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bind_param('s', $taskId);

                // Execute statement
                $stmt->execute();

                // get the result
                $result = $stmt->get_result();
                    
                // Check if there are any results
                if ($result->num_rows > 0) {
                    
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        if (isset($_SESSION["id"]) && $_SESSION["id"] == $row["user_id"]) {
                            echo "<div class='container user_input'><p>" . $row["answer"] . "</p><span><em><a href='./profile.php?user_id=" . $row["user_id"] . "'>" . $row["name"] . "</a></em></span><span class='date'>" . $row["date_only"] . "</span></div>";
                        } else {
                            echo "<div class='container'><p>" . $row["answer"] . "</p><span><em><a href='./profile.php?user_id=" . $row["user_id"] . "'>" . $row["name"] . "</a></em></span><span class='date'>" . $row["date_only"] . "</span></div>";
                        }
                    }
                } else {
                    echo "<p>No answer found for this question.</p>";
                }

                // Close statement
                $stmt->close();
            } else {
                echo "<p>Task ID is missing from the URL.</p>";
            }
        ?>
    </main>
    
</body>
</html>

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
        include "./includes/header.php";
    ?>

    <main>
        <?php
            // Include database connection
            include "./includes/db_connection.php";

            // SQL query to fetch all tasks from the database
            $sql = "SELECT tasks.id, tasks.user_id, tasks.title, DATE(tasks.reg_date) AS date_only, users.name FROM tasks INNER JOIN users ON tasks.user_id = users.id";

            // Get result
            $result = $conn->query($sql);

            // Check if there are any tasks
            if ($result->num_rows > 0) {

                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $taskId = $row["id"];
                    $userId = $row["user_id"];
                    $title = $row["title"];

                    // Check if the task belongs to the logged-in user
                    if (isset($_SESSION["id"]) && $_SESSION["id"] == $userId) {
                        echo "<div class='container user_input'><a href='./question.php?task_id=$taskId'><h2>$title</h2></a><span><em><a href='./profile.php?user_id=" . $userId . "'>" . $row["name"] . "</a></em></span><span class='date'>" . $row["date_only"] . "</span></div>";
                    } else { 
                        echo "<div class='container'><a href='./question.php?task_id=$taskId'><h2>$title</h2></a><span><em><a href='./profile.php?user_id=" . $userId . "'>" . $row["name"] . "</a></em></span><span class='date'>" . $row["date_only"] . "</span></div>";
                    }
                }
            } else {
                echo "<p>No question found.</p>";
            }

            // Close the database connection
            $conn->close();
        ?>
    </main>
</body>
</html>
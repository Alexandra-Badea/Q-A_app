<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <?php
        // Include header
        include "./includes/header.php";
    ?>

    <main>

        <?php
    
            // Include the database connection
            include "./includes/db_connection.php";
    
            // Check if user_id is provided in the URL
            if (isset($_GET["user_id"])) {
                $userId = $_GET["user_id"];
        ?>

        <?php
            // Fetch the user data
            $sql = "SELECT users.name, tasks.title, tasks.id FROM users INNER JOIN tasks ON tasks.user_id=users.id WHERE users.id = ?";
        
            // Prepare the statement
            $stmt = $conn->prepare($sql);
        
            // Bind parameters
            $stmt->bind_param("i", $userId);
        
            // Execute the statement
            $stmt->execute();
        
            // Get the result
            $result = $stmt->get_result();
        
            // Check if there are any results
            if ($result->num_rows > 0) {
                // Display user'name if not logged
                if (!isset($_SESSION["id"]) && !isset($_SESSION["name"])) {
                    $row = $result->fetch_assoc();
                    echo "<h2 class='user_name'>" . $row["name"] . "</h2>";
                    $result->data_seek(0);
                }

                // Output data of each row
                while ($row = $result->fetch_assoc()) {   
                    echo "<div class='container'><a href='./question.php?task_id=" . $row["id"] . "'><h2>" . $row["title"] . "</h2></a></div>";
                }
            } else {
                echo "<p>No questions found for this user.</p>";
            }
        
            // Close  statement
            $stmt->close();
        } else {
            echo "<p>User ID is missing from the URL.</p>";
        }
        
        ?>
    </main>
</body>
</html>
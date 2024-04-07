<header>
    <a href="./" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''); ?>>Questions</a>

    <?php
        // Start session
        session_start();

        // Check if user is logged in
        if(isset($_SESSION["id"]) && isset($_SESSION["name"])) {

            // User is logged in, display "Ask " link, user name and "Logout" link
            echo "<a href='./ask.php' " . (basename($_SERVER['PHP_SELF']) == 'ask.php' ? 'class="active"' : '') . ">Ask</a>";
            echo "<a class='right line" . (basename($_SERVER['PHP_SELF']) == 'profile.php' && isset($_GET['user_id']) && $_GET['user_id'] == $_SESSION['id'] ? " active" : "") . "' href='./profile.php?user_id=" . $_SESSION["id"] . "'><h2>" . $_SESSION['name'] . "</h2></a>";
            echo "<a href='logout.php'>Logout</a>";
        } else {
            echo "<a class='right line" . (basename($_SERVER['PHP_SELF']) == 'register.php' ? ' active' : '') . "' href='./register.php'>Register</a>";
            echo "<a href='./login.php' " . (basename($_SERVER['PHP_SELF']) == 'login.php' ? 'class="active"' : '') . ">Login</a>";
        }
    ?>
</header>
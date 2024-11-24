<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener Installer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>URL Shortener Installer</h1>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $servername = $_POST['servername'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $dbname = $_POST['dbname'];

            // Attempt to connect to the database
            $conn = new mysqli($servername, $username, $password);

            if ($conn->connect_error) {
                echo "<p class='error-message'>Connection failed: " . $conn->connect_error . "</p>";
            } else {
                // Create the database if it doesn't exist
                $createDbSql = "CREATE DATABASE IF NOT EXISTS $dbname";
                if ($conn->query($createDbSql) === TRUE) {
                    echo "<p class='success-message'>Database created successfully or already exists.</p>";

                    // Connect to the newly created database
                    $conn->select_db($dbname);

                    // Create the required table
                    $createTableSql = "
                        CREATE TABLE IF NOT EXISTS urls (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            short_code VARCHAR(10) NOT NULL UNIQUE,
                            original_url TEXT NOT NULL,
                            clicks INT DEFAULT 0,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        );
                    ";

                    if ($conn->query($createTableSql) === TRUE) {
                        echo "<p class='success-message'>Table created successfully or already exists.</p>";

                        // Write the config file
                        $configContent = "<?php\n"
                            . "\$servername = \"$servername\";\n"
                            . "\$username = \"$username\";\n"
                            . "\$password = \"$password\";\n"
                            . "\$dbname = \"$dbname\";\n"
                            . "\n"
                            . "\$conn = new mysqli(\$servername, \$username, \$password, \$dbname);\n"
                            . "if (\$conn->connect_error) {\n"
                            . "    die(\"Connection failed: \" . \$conn->connect_error);\n"
                            . "}\n"
                            . "?>";

                        file_put_contents('config.php', $configContent);

                        echo "<p class='success-message'>Configuration file created successfully.</p>";
                        echo '<a class="back-link" href="index.php">Proceed to URL Shortener</a>';
                    } else {
                        echo "<p class='error-message'>Error creating table: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p class='error-message'>Error creating database: " . $conn->error . "</p>";
                }
            }
            $conn->close();
        } else {
        ?>
            <form action="install.php" method="POST">
                <label for="servername">Server Name:</label><br>
                <input type="text" id="servername" name="servername" value="localhost" required><br><br>
                
                <label for="username">Database Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Database Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <label for="dbname">Database Name:</label><br>
                <input type="text" id="dbname" name="dbname" required><br><br>

                <button type="submit">Install URL Shortener</button>
            </form>
        <?php
        }
        ?>
    </div>
</body>
</html>

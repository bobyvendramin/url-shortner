<?php
require 'config.php'; // Load database credentials
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $originalUrl = trim($_POST['url']);
            $customCode = isset($_POST['custom_code']) ? trim($_POST['custom_code']) : '';

            // Validate the URL
            if (!filter_var($originalUrl, FILTER_VALIDATE_URL)) {
                echo '<p class="error-message">Invalid URL. Please enter a valid one.</p>';
                echo '<a class="back-link" href="index.php">Go Back</a>';
                exit();
            }

            if (!empty($customCode)) {
                // Check if the custom shortcode is already in use
                $stmt = $conn->prepare("SELECT * FROM urls WHERE short_code = ?");
                $stmt->bind_param("s", $customCode);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo '<p class="error-message">The custom shortcode is already taken. Please choose another one.</p>';
                    echo '<a class="back-link" href="index.php">Go Back</a>';
                    exit();
                }

                $shortCode = $customCode;
            } else {
                // Generate a unique short code
                $shortCode = generateShortCode();
            }

            // Insert into the database
            $stmt = $conn->prepare("INSERT INTO urls (short_code, original_url) VALUES (?, ?)");
            $stmt->bind_param("ss", $shortCode, $originalUrl);

            if ($stmt->execute()) {
                echo "<h1>URL Shortened Successfully!</h1>";
                echo "<p>Shortened URL: <a href='http://s.kakoi.com.br/{$shortCode}' target='_blank'>http://s.kakoi.com.br/{$shortCode}</a></p>";
                echo '<a class="back-link" href="index.php">Shorten Another URL</a>';
            } else {
                echo '<p class="error-message">Something went wrong. Please try again.</p>';
                echo '<a class="back-link" href="index.php">Go Back</a>';
            }

            $stmt->close();
            $conn->close();
        }

        function generateShortCode($length = 6) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $shortCode = '';

            for ($i = 0; $i < $length; $i++) {
                $shortCode .= $characters[rand(0, $charactersLength - 1)];
            }

            return $shortCode;
        }
        ?>
    </div>
</body>
</html>
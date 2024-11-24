<?php
require 'config.php'; // Load database credentials

// Retrieve all URL data
$sql = "SELECT short_code, original_url, clicks FROM urls ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener Analytics</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>URL Shortener Analytics</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Short Code</th>
                <th>Original URL</th>
                <th>Clicks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='http://s.kakoi.com.br/{$row['short_code']}'>http://s.kakoi.com.br/{$row['short_code']}</a></td>";
                    echo "<td>{$row['original_url']}</td>";
                    echo "<td>{$row['clicks']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No URLs found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>

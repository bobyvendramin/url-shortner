<?php
require 'config.php'; // Load database credentials

$shortCode = isset($_GET['code']) ? trim($_GET['code']) : '';
$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

$logStmt = $conn->prepare("INSERT INTO url_visits (short_code, ip_address, user_agent) VALUES (?, ?, ?)");
$logStmt->bind_param("sss", $shortCode, $ipAddress, $userAgent);
$logStmt->execute();
$logStmt->close();

if (empty($shortCode)) {
    die('Invalid request. Short code is required.');
}

// Look up the short code in the database
$stmt = $conn->prepare("SELECT original_url, clicks FROM urls WHERE short_code = ?");
$stmt->bind_param("s", $shortCode);
$stmt->execute();
$stmt->bind_result($originalUrl, $clicks);

if ($stmt->fetch()) {
    // Update the clicks count for the URL
    $stmt->close();
    $clicks++;

    $updateStmt = $conn->prepare("UPDATE urls SET clicks = ? WHERE short_code = ?");
    $updateStmt->bind_param("is", $clicks, $shortCode);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect to the original URL
    header("Location: " . $originalUrl);
    exit();
} else {
    echo "Invalid URL. Please check your link.";
}

$stmt->close();
$conn->close();
?>

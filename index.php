<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <script>
        function addPrefixIfNeeded() {
            const urlField = document.getElementById('url');
            let urlValue = urlField.value.trim();

            if (urlValue && !/^https?:\/\//i.test(urlValue)) {
                urlField.value = 'https://' + urlValue;
            }
        }
    </script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Simple URL Shortener</h1>
    <form action="shorten.php" method="POST">
        <label for="url">Enter URL to shorten:</label><br>
        <input type="url" id="url" name="url" required onblur="addPrefixIfNeeded()"><br><br>

        <label for="custom_code">Custom Shortcode (optional):</label><br>
        <input type="text" id="custom_code" name="custom_code" pattern="[a-zA-Z0-9]{1,10}">
        <small>Up to 10 alphanumeric characters.</small><br><br>

        <button type="submit">Shorten URL</button>
    </form>
    
    <a class="analytics-link" href="analytics.php">View Analytics</a>

</body>
</html>

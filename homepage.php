<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo "Welcome " . $_SESSION['username'] . "!";
    echo $_SESSION['id'];
} else {
    echo "Please log in first to see this page.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotistats</title>
</head>
<body>
    <h1>Homepage</h1>
    <h2>Click <a href="fileUpload.php">here</a> to get to the file upload.</h2> 
</body>
</html>
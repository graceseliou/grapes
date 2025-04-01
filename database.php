<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Password123!"; // Use your actual MySQL password
$dbname = "grapes_folders";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all folder data
$sql = "SELECT * FROM folders";
$result = $conn->query($sql);

?>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database - Grapes Folders</title>
  </head>
  <body>
    <h1>Folder Database</h1>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . $row['folder_name'] . "</h3>";
            echo "<img src='" . $row['folder_image'] . "' alt='Folder Image' style='width: 100px; height: 100px;'>";
            echo "<p>" . $row['folder_description'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "No folders found.";
    }
    $conn->close();
    ?>

  </body>
</html>


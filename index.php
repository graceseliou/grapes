<?php
// PHP code to handle the folder creation button if needed
// This can later be extended to store folder data in the database.
// Database connection
$servername = "localhost";
$username = "root";
$password = "Password123!"; // Use your actual MySQL password
$dbname = "grapes_folders";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted (for both create and edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's an edit request
    if (isset($_GET['edit_id'])) {
        // Update folder
        $edit_id = $_GET['edit_id'];
        $folder_image = $_POST['folder_image'];
        $folder_name = $_POST['folder_name'];
        $folder_description = $_POST['folder_description'];

        // Update query
        $sql = "UPDATE folders SET folder_image = '$folder_image', folder_name = '$folder_name', folder_description = '$folder_description' WHERE id = $edit_id";

        if ($conn->query($sql) === TRUE) {
            echo "Folder updated successfully";
        } else {
            echo "Error updating folder: " . $conn->error;
        }
    } else {
        // Create new folder
        $folder_image = $_POST['folderImageLink'];
        $folder_name = $_POST['folderName'];
        $folder_description = $_POST['folderDescription'];

        // Insert query
        $sql = "INSERT INTO folders (folder_image, folder_name, folder_description) 
                VALUES ('$folder_image', '$folder_name', '$folder_description')";

        if ($conn->query($sql) === TRUE) {
            echo "New folder created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle folder deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete folder
    $sql = "DELETE FROM folders WHERE id = $delete_id";

    if ($conn->query($sql) === TRUE) {
        echo "Folder deleted successfully";
    } else {
        echo "Error deleting folder: " . $conn->error;
    }
}

// Fetch folder data for editing
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch folder data for editing
    $sql = "SELECT * FROM folders WHERE id = $edit_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $folder = $result->fetch_assoc();
    }

    $conn->close();
}

$conn->close();
?>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eti 300w - The Grapes' Customizable Spotify Folders Project</title>
    <link rel="stylesheet" href="styles.css">
    <script>

function togglePopout() {
        var popout = document.getElementById("folderPopout");
        popout.style.display = (popout.style.display === "none" || popout.style.display === "") ? "block" : "none";
      }

      // Function to close the create popout
function closeCreatePopout() {
    var createPopout = document.getElementById("createPopout");
    createPopout.style.display = "none"; // Close the create popout
    // Reset form fields
    document.getElementById("folderImageLink").value = '';
    document.getElementById("folderName").value = '';
    document.getElementById("folderDescription").value = '';
}

// Function to close the edit popout
function closeEditPopout() {
    var editPopout = document.getElementById("editPopout");
    editPopout.style.display = "none"; // Close the edit popout
}

// Function to close after update and hide the edit popout
function closeAfterUpdate() {
    closeEditPopout();
    // Additional actions after update (e.g., refresh page or update content)
}

    </script>
  </head>
  <body>
    <h1>Eti 300w - The Grapes' Customizable Spotify Folders Project</h1>
    
    <!-- "Create New Folder" Button -->
    <button onclick="togglePopout()">Create New Folder</button>

    <!-- Popout Modal for Folder Creation -->
    <div id="folderPopout" style="display: none; background: rgba(0, 0, 0, 0.5); padding: 20px; position: fixed; top: 0; left: 0; right: 0; bottom: 0; justify-content: center; align-items: center;">
      <div style="background: white; padding: 20px; width: 300px; margin: auto;">
        <h3>Create New Folder</h3>
        <form method="POST" action="index.php">
          <label for="folderImageLink">Folder Image Link:</label>
          <input type="text" id="folderImageLink" name="folderImageLink" placeholder="Enter image URL" style="width: 100%; margin-bottom: 10px;"><br>

          <label for="folderName">Folder Name:</label>
          <input type="text" id="folderName" name="folderName" placeholder="Enter folder name" style="width: 100%; margin-bottom: 10px;"><br>

          <label for="folderDescription">Folder Description:</label>
          <input type="text" id="folderDescription" name="folderDescription" placeholder="Enter folder description" style="width: 100%; margin-bottom: 10px;"><br>

          <button type="submit">Create</button>
        </form>
        <button type="button" onclick="closeCreatePopout()">Close</button>
      </div>
    </div>

    <hr>

    <!-- Row of Folder Images with Edit/Delete buttons -->
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
      <?php
      // Fetch all folders from the database
      $conn = new mysqli($servername, $username, $password, $dbname);
      $sql = "SELECT * FROM folders";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<div style='margin-bottom: 20px;'>";
              echo "<img src='" . $row['folder_image'] . "' alt='Folder Image' style='width: 100px; height: 100px;'><br>";
              echo "<h4>" . $row['folder_name'] . "</h4>";
              echo "<p>" . $row['folder_description'] . "</p>";
              echo "<a href='index.php?edit_id=" . $row['id'] . "'><button>Edit</button></a> ";
              echo "<div><a href='index.php?delete_id=" . $row['id'] . "'><button>Delete</button></a></div>";
              echo "</div>";
          }
      } else {
          echo "No folders found.";
      }
      $conn->close();
      ?>	

<?php
if (isset($folder)) {
?>
  <!-- Popout Modal for Folder Editing (if editing exists) -->
  <div id="editPopout" style="display: block; background: rgba(0, 0, 0, 0.5); padding: 20px; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000;">
    <div style="background: white; padding: 20px; width: 300px; margin: auto;">
        <h3>Edit Folder</h3>
        <form method="POST" action="index.php?edit_id=<?php echo $folder['id']; ?>" enctype="multipart/form-data">
            <label for="folder_image">Folder Image URL:</label><br>
            <input type="text" name="folder_image" value="<?php echo $folder['folder_image']; ?>" required><br><br>

            <label for="folder_name">Folder Name:</label><br>
            <input type="text" name="folder_name" value="<?php echo $folder['folder_name']; ?>" required><br><br>

            <label for="folder_description">Folder Description:</label><br>
            <input type="text" name="folder_description" value="<?php echo $folder['folder_description']; ?>" required><br><br>

            <input type="submit" value="Update Folder" onclick="closeAfterUpdate()"> <!-- Close after update -->
        </form>
        <button type="button" onclick="closeEditPopout()">Close</button> <!-- Close button for edit popout -->
    </div>
  </div>
<?php
}
?>

    </div>
  </body>
</html>

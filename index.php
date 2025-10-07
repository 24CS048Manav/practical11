<?php
// =====================================
// Simple PHP MySQL Student Database App
// =====================================

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "prac11";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// =====================================
// Create Table (if not exists)
// =====================================
$conn->query("CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  course VARCHAR(100)
)");

// =====================================
// Insert Data
// =====================================
if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $course = $_POST['course'];

  if (!empty($name) && !empty($email) && !empty($course)) {
    $sql = "INSERT INTO students (name, email, course) VALUES ('$name', '$email', '$course')";
    if ($conn->query($sql)) {
      echo "<p style='color:green;text-align:center;'>✅ Student Added Successfully!</p>";
    }
  } else {
    echo "<p style='color:red;text-align:center;'>❌ All fields are required!</p>";
  }
}

// =====================================
// Delete Data
// =====================================
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM students WHERE id=$id");
  echo "<p style='color:red;text-align:center;'>🗑️ Record Deleted!</p>";
}

// =====================================
// Search Functionality
// =====================================
$searchQuery = "";
if (isset($_POST['search'])) {
  $searchQuery = $_POST['search_text'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Database</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      margin: 20px;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    form {
      background: #fff;
      padding: 20px;
      width: 350px;
      margin: 20px auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input[type=text], input[type=email] {
      width: 100%;
      padding: 8px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    input[type=submit] {
      width: 100%;
      background: #007bff;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
    }
    input[type=submit]:hover {
      background: #0056b3;
    }
    table {
      width: 80%;
      margin: 20px auto;
      border-collapse: collapse;
      background: white;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th {
      background: #007bff;
      color: white;
    }
    a {
      color: #007bff;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .search-box {
      width: 80%;
      margin: 10px auto;
      text-align: center;
    }
    .search-box input[type=text] {
      width: 60%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .search-box input[type=submit] {
      width: auto;
      padding: 8px 15px;
    }
  </style>
</head>
<body>

<h2>🎓 Student Database Management</h2>

<!-- Add Student Form -->
<form method="POST">
  <h3>Add Student</h3>
  <input type="text" name="name" placeholder="Enter Name" required>
  <input type="email" name="email" placeholder="Enter Email" required>
  <input type="text" name="course" placeholder="Enter Course" required>
  <input type="submit" name="submit" value="Add Student">
</form>

<!-- Search Form -->
<div class="search-box">
  <form method="POST">
    <input type="text" name="search_text" placeholder="Search by name..." value="<?php echo $searchQuery; ?>">
    <input type="submit" name="search" value="Search">
  </form>
</div>

<!-- Display Data -->
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Course</th>
    <th>Action</th>
  </tr>
  <?php
  $sql = "SELECT * FROM students";
  if (!empty($searchQuery)) {
    $sql = "SELECT * FROM students WHERE name LIKE '%$searchQuery%'";
  }
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['name']}</td>
              <td>{$row['email']}</td>
              <td>{$row['course']}</td>
              <td><a href='?delete={$row['id']}' onclick='return confirm(\"Delete record?\");'>Delete</a></td>
            </tr>";
    }
  } else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
  }
  ?>
</table>

</body>
</html>

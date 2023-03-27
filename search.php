<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle search form submission

    // Get search query from form
    $search_query = $_POST["search_query"];

    // Build SQL query
    $sql = "SELECT * FROM books WHERE name LIKE '%$search_query%' OR author LIKE '%$search_query%'";

    // Execute SQL query
    $result = $conn->query($sql);

    // Convert result to associative array
    $books = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
} else {
    // Get all books from database
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    // Convert result to associative array
    $books = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
}

// Close database connection
$conn->close();
?>
<?php
// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query books from database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            'id' => $row["ID"],
            'name' => htmlspecialchars($row["Name"]),
            'author' => htmlspecialchars($row["Author"]),
            'description' => htmlspecialchars($row["Description"]),
            'availability' => htmlspecialchars($row["Availability"])
        ];
    }
}

$conn->close();

return $books;
?>
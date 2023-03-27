<?php

$books = include 'get_books.php';

// Connect to MySQL database
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
    if (isset($_POST["save_changes"])) {
        // Handle save changes form submission

        // Get data from form
        $id = $_POST["id"];
        $name = $_POST["name"];
        $author = $_POST["author"];
        $description = $_POST["description"];
        $availability = $_POST["availability"];

        // Update data in database
        $stmt = $conn->prepare("UPDATE books SET name=?, author=?, description=?, availability=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $author, $description, $availability, $id);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Handle add book form submission

        // Get data from form
        $name = $_POST["name"];
        $author = $_POST["author"];
        $description = $_POST["description"];
        $availability = $_POST["availability"];

        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO books (name, author, description, availability) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $author, $description, $availability);
        if ($stmt->execute()) {
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    // Search for books by name or author
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM books WHERE name LIKE '%$search%' OR author LIKE '%$search%'";
        $result = $conn->query($sql);

        $books = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
    }
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <h1 class="m-5 text-center">Library</h1>
    <table class="table table-striped-columns" id="bookTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Author</th>
                <th>Description</th>
                <th>Availability</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <tr data-id="<?php echo $book['id']; ?>">
                        <td>
                            <?= $book['name'] ?>
                        </td>
                        <td>
                            <?= $book['author'] ?>
                        </td>
                        <td>
                            <?= $book['description'] ?>
                        </td>
                        <td>
                            <?= $book['availability'] ?>
                        </td>
                        <td><button class="btn btn-primary editButton" data-bs-toggle="modal"
                                data-bs-target="#editModal">Edit</button>
                            <button class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No books found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <form method="GET" class="from" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="search">Search:</label>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search...">
        <button type="submit" class="btn btn-info">Search</button>
    </form>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
        Add Book
    </button>

    <!-- Add Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">Add Book</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" class="form-control" id="author" name="author">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="availability">Availability:</label>
                            <select class="form-control" id="availability" name="availability">
                                <option value="">--Select--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer m-1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content p-2">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Book</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post">
                    <input type="hidden" name="save_changes" value="1">
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="edit-name">Name:</label>
                            <input type="text" class="form-control" id="edit-name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="edit-author">Author:</label>
                            <input type="text" class="form-control" id="edit-author" name="author">
                        </div>
                        <div class="form-group">
                            <label for="edit-description">Description:</label>
                            <textarea class="form-control" id="edit-description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-availability">Availability:</label>
                            <select class="form-control" id="edit-availability" name="availability">
                                <option value="">--Select--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer m-1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info" data-bs-dismiss="modal">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="edit_delete.js"></script>

</body>

</html>
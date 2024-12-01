<?php
$conn = new mysqli('localhost', 'root', '', 'de251');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$query = $_GET['query'] ?? '';
$searchTerm = "%" . $query . "%";

$sql = $query ?
    "SELECT * FROM users WHERE name LIKE ? OR email LIKE ? OR subject LIKE ?" :
    "SELECT * FROM users";

$stmt = $conn->prepare($sql);
if ($query) $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

echo "<h1>Search Results</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'>
        <tr><th>Users ID</th><th>Name</th><th>Email</th><th>Subject</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['user_id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['subject']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found for'" . htmlspecialchars($query) . "'</p>";
}

$stmt->close();
$conn->close();
?>
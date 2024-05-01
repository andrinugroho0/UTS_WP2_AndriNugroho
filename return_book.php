<?php
include 'Book.php';
include 'Library.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];

    $library = new Library();
    $library->returnBook($title);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
</head>
<body>
    <h2>Return Book</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Title: <input type="text" name="title"><br><br>
        <input type="submit" value="Return Book">
    </form>
</body>
</html>

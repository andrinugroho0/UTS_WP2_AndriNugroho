<?php
include 'Book.php';
include 'Library.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];

    $library = new Library();
    $library->borrowBook($title);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
</head>
<body>
    <h2>Borrow Book</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Title: <input type="text" name="title"><br><br>
        <input type="submit" value="Borrow Book">
    </form>
</body>
</html>

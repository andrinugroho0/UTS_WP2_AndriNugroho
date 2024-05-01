<?php
include 'Book.php';
include 'Library.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];

    $newBook = new Book($title, $author);
    $library = new Library();
    $library->addBook($newBook);

    echo "Book '$title' by $author has been added to the library.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
</head>
<body>
    <h2>Add New Book</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Title: <input type="text" name="title"><br><br>
        Author: <input type="text" name="author"><br><br>
        <input type="submit" value="Add Book">
    </form>
</body>
</html>

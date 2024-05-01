<?php
include 'Book.php';
include 'Library.php';

$library = new Library();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
</head>
<body>
    <h2>Available Books</h2>
    <?php $library->displayAvailableBooks(); ?>
</body>
</html>

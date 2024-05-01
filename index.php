<?php
require_once 'Book.php';
require_once 'Library.php';

session_start();

// Inisialisasi perpustakaan jika belum ada di session
if (!isset($_SESSION['library'])) {
    $_SESSION['library'] = new Library();
}

// Mengambil objek Library dari session
$library = $_SESSION['library'];

// Proses tambah buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $status = $_POST['status'];
    $library->addBook($title, $author, $year, $isbn, $publisher, $status);
}

// Proses hapus buku
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['index'])) {
    $index = $_GET['index'];
    $library->removeBook($index);
}

// Proses pinjam buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrow_book'])) {
    $index = $_POST['book_index'];
    $borrowDate = date('Y-m-d');
    $returnDate = date('Y-m-d', strtotime('+7 days')); // Contoh: Batas pinjam 7 hari dari sekarang
    $library->borrowBook($index, $borrowDate, $returnDate);
}

// Proses kembalikan buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
    $index = $_POST['borrowed_book_index'];
    $library->returnBook($index);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Perpustakaan</h1>
    <h2>Tambah Buku</h2>
    <form method="post" action="index.php">
        <label for="title">Judul:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="author">Penulis:</label><br>
        <input type="text" id="author" name="author" required><br>
        <label for="year">Tahun Terbit:</label><br>
        <input type="text" id="year" name="year" required><br>
        <label for="isbn">ISBN:</label><br>
        <input type="text" id="isbn" name="isbn" required><br>
        <label for="publisher">Penerbit:</label><br>
        <input type="text" id="publisher" name="publisher" required><br>
        <label for="status">Status:</label><br>
        <select id="status" name="status">
            <option value="Tersedia" selected>Tersedia</option>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Hilang">Hilang</option>
        </select><br>
        <button type="submit" name="add_book">Tambah Buku</button>
    </form>

    <h2>Daftar Buku</h2>
    <ul>
        <?php foreach ($library->getBooks() as $index => $book): ?>
            <li>
                <?php echo $book->title; ?> (<?php echo $book->author; ?>, <?php echo $book->year; ?>)
                <br>
                ISBN: <?php echo $book->isbn; ?>, Penerbit: <?php echo $book->publisher; ?>, Status: <?php echo $book->status; ?>
                <form method="post" action="index.php">
                    <input type="hidden" name="book_index" value="<?php echo $index; ?>">
                    <button type="submit" name="borrow_book">Pinjam</button>
                </form>
                <a href="index.php?action=remove&index=<?php echo $index; ?>">Hapus</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Daftar Buku Dipinjam</h2>
    <ul>
        <?php foreach ($library->getBorrowedBooks() as $index => $borrowedBook): ?>
            <li>
                <?php echo $borrowedBook['book']->title; ?> (<?php echo $borrowedBook['book']->author; ?>, <?php echo $borrowedBook['book']->year; ?>)
                <?php if ($library->checkDueDate($index, date('Y-m-d'))): ?>
                    <span style="color: red;">Melebihi batas pinjam!</span>
                <?php else: ?>
                    <span>Batas pinjam: <?php echo $borrowedBook['returnDate']; ?></span>
                    <form method="post" action="index.php">
                        <input type="hidden" name="borrowed_book_index" value="<?php echo $index; ?>">
                        <button type="submit" name="return_book">Kembalikan</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

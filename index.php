<?php
require_once 'Book.php'; // Memasukkan file Book.php yang berisi definisi kelas Book
require_once 'Library.php'; // Memasukkan file Library.php yang berisi definisi kelas Library

session_start(); // Memulai sesi PHP

// Inisialisasi perpustakaan jika belum ada di session
if (!isset($_SESSION['library'])) {
    $_SESSION['library'] = new Library(); // Jika objek Library tidak ada dalam session, inisialisasikan objek baru
}

// Mengambil objek Library dari session
$library = $_SESSION['library']; // Mendapatkan objek Library dari session

// Proses tambah buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    // Mengambil data dari form
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $status = $_POST['status'];
    // Menambahkan buku baru ke dalam objek Library
    $library->addBook($title, $author, $year, $isbn, $publisher, $status);
}

// Proses hapus buku
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['index'])) {
    // Mengambil indeks buku yang akan dihapus
    $index = $_GET['index'];
    // Menghapus buku dari objek Library
    $library->removeBook($index);
}

// Proses pinjam buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrow_book'])) {
    // Mengambil indeks buku yang akan dipinjam
    $index = $_POST['book_index'];
    // Mendapatkan tanggal pinjam dan tanggal pengembalian yang sesuai
    $borrowDate = date('Y-m-d');
    $returnDate = date('Y-m-d', strtotime('+7 days')); // Contoh: Batas pinjam 7 hari dari sekarang
    // Meminjam buku dari objek Library
    $library->borrowBook($index, $borrowDate, $returnDate);
}

// Proses kembalikan buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
    // Mengambil indeks buku yang akan dikembalikan
    $index = $_POST['borrowed_book_index'];
    // Mengembalikan buku ke objek Library
    $library->returnBook($index);
}

// Proses pencarian buku
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    // Mengambil kata kunci pencarian
    $keyword = $_GET['search'];
    // Melakukan pencarian buku berdasarkan kata kunci
    $searchResults = $library->searchBooks($keyword);
}

// Proses pengurutan buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sort_books'])) {
    // Mengambil kriteria pengurutan
    $sortBy = $_POST['sort_by'];
    // Mengurutkan buku berdasarkan tahun terbit
    $library->sortBooksByYear($sortBy);
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
    <div class="container">
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

        <h2>Pencarian Buku</h2>
        <form method="get" action="index.php">
            <label for="search">Cari Buku:</label><br>
            <input type="text" id="search" name="search" required><br>
            <button type="submit">Cari</button>
        </form>

        <?php if (!empty($searchResults)): ?>
            <div class="search-results">
                <h2>Hasil Pencarian</h2>
                <ul>
                    <?php foreach ($searchResults as $book): ?>
                        <li>
                            <?php echo $book->title; ?> (<?php echo $book->author; ?>, <?php echo $book->year; ?>)
                            <br>
                            ISBN: <?php echo $book->isbn; ?>, Penerbit: <?php echo $book->publisher; ?>, Status: <?php echo $book->status; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <h2>Daftar Buku</h2>
        <form method="post" action="index.php">
            <label for="sort_by">Urutkan berdasarkan tahun terbit:</label>
            <select id="sort_by" name="sort_by">
                <option value="asc">Tahun Terbit (Terbaru ke Terlama)</option>
                <option value="desc">Tahun Terbit (Terlama ke Terbaru)</option>
            </select>
            <button type="submit" name="sort_books">Urutkan</button>
        </form>
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
    </div>
</body>
</html>

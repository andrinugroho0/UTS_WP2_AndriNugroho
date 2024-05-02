<?php
require_once 'Book.php';

class Library {
    private $books = []; // Menyimpan daftar buku yang tersedia di perpustakaan
    private $borrowedBooks = []; // Menyimpan daftar buku yang sedang dipinjam

    // Metode untuk mencari buku berdasarkan kata kunci judul atau penulis
    public function searchBooks($keyword) {
        $results = [];
        foreach ($this->books as $book) {
            if (stripos($book->title, $keyword) !== false || stripos($book->author, $keyword) !== false) {
                $results[] = $book;
            }
        }
        return $results;
    }

    // Metode untuk menambahkan buku baru ke dalam perpustakaan
    public function addBook($title, $author, $year, $isbn, $publisher, $status = "Tersedia") {
        $book = new Book($title, $author, $year, $isbn, $publisher, $status);
        $this->books[] = $book;
    }

    // Metode untuk meminjam buku dari perpustakaan
    public function borrowBook($index, $borrowDate, $returnDate) {
        if (isset($this->books[$index])) {
            $book = $this->books[$index];
            $this->borrowedBooks[] = [
                'book' => $book,
                'borrowDate' => $borrowDate,
                'returnDate' => $returnDate
            ];
            unset($this->books[$index]);
            $this->books = array_values($this->books); // Reindex array
        }
    }

    // Metode untuk mengembalikan buku yang telah dipinjam
    public function returnBook($index) {
        if (isset($this->borrowedBooks[$index])) {
            $book = $this->borrowedBooks[$index]['book'];
            $this->books[] = $book;
            unset($this->borrowedBooks[$index]);
            $this->borrowedBooks = array_values($this->borrowedBooks); // Reindex array
        }
    }

    // Metode untuk memeriksa apakah buku telah melewati batas pinjam
    public function checkDueDate($index, $currentDate) {
        if (isset($this->borrowedBooks[$index])) {
            $dueDate = $this->borrowedBooks[$index]['returnDate'];
            if ($currentDate > $dueDate) {
                return true; // Melebihi batas pinjam
            }
        }
        return false; // Belum melewati batas pinjam
    }

    // Metode untuk mendapatkan daftar buku yang tersedia di perpustakaan
    public function getBooks() {
        return $this->books;
    }

    // Metode untuk mendapatkan daftar buku yang sedang dipinjam
    public function getBorrowedBooks() {
        return $this->borrowedBooks;
    }

    // Metode untuk menghapus buku dari perpustakaan
    public function removeBook($index) {
        if (isset($this->books[$index])) {
            unset($this->books[$index]);
            $this->books = array_values($this->books); // Reindex array
        }
    }

    // Metode untuk mengurutkan daftar buku berdasarkan tahun terbit
    public function sortBooksByYear($sortBy) {
        // Urutkan buku berdasarkan tahun terbit
        if ($sortBy === 'asc') {
            usort($this->books, function ($a, $b) {
                return $a->year - $b->year;
            });
        } elseif ($sortBy === 'desc') {
            usort($this->books, function ($a, $b) {
                return $b->year - $a->year;
            });
        }
    }
}
?>

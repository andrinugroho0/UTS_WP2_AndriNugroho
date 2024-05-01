<?php
require_once 'Book.php';

class Library {
    protected $books;
    protected $maxBooksPerUser = 3; // Batasan peminjaman per pengguna

    public function __construct() {
        $this->books = [];
    }

    public function addBook(Book $book) {
        $this->books[] = $book;
    }

    public function getBooks() {
        return $this->books;
    }

    // Pencarian buku berdasarkan judul atau penulis
    public function searchBooks($query) {
        $results = [];
        foreach ($this->books as $book) {
            if (strpos(strtolower($book->getTitle()), strtolower($query)) !== false ||
                strpos(strtolower($book->getAuthor()), strtolower($query)) !== false) {
                $results[] = $book;
            }
        }
        return $results;
    }

    // Pengurutan buku berdasarkan tahun terbit atau nama penulis
    public function sortBooks($criteria) {
        // Implementasi pengurutan
    }

    // Menghapus buku dari koleksi berdasarkan ID buku
    public function removeBook($bookId) {
        // Implementasi penghapusan
    }

    // Hitung denda keterlambatan pengembalian buku
    public static function calculateLateFee($dueDate) {
        // Implementasi perhitungan denda
    }
}
?>

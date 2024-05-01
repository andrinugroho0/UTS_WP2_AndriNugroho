<?php
class Book {
    public $title;
    public $author;
    public $year;
    public $isbn; // Menyimpan ISBN buku
    public $publisher; // Menyimpan nama penerbit buku
    public $status; // Menyimpan status buku (misalnya: tersedia, dipinjam, dll.)

    public function __construct($title, $author, $year, $isbn, $publisher, $status = "Tersedia") {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->isbn = $isbn;
        $this->publisher = $publisher;
        $this->status = $status;
    }
}
?>

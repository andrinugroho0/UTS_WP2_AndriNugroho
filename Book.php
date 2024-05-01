<?php
class Book {
    public $title;
    public $author;
    public $availability;

    public function __construct($title, $author) {
        $this->title = $title;
        $this->author = $author;
        $this->availability = true;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function isAvailable() {
        return $this->availability;
    }

    public function borrowBook() {
        $this->availability = false;
    }

    public function returnBook() {
        $this->availability = true;
    }
}
?>

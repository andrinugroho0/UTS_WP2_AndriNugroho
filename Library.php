<?php
class Library {
    public $books = [];

    public function addBook(Book $book) {
        $this->books[] = $book;
    }

    public function displayAvailableBooks() {
        foreach ($this->books as $book) {
            if ($book->isAvailable()) {
                echo "Title: " . $book->getTitle() . " | Author: " . $book->getAuthor() . "<br>";
            }
        }
    }

    public function borrowBook($title) {
        foreach ($this->books as $book) {
            if ($book->getTitle() == $title && $book->isAvailable()) {
                $book->borrowBook();
                echo "Book '$title' has been borrowed.<br>";
                return;
            }
        }
        echo "Sorry, book '$title' is not available.<br>";
    }

    public function returnBook($title) {
        foreach ($this->books as $book) {
            if ($book->getTitle() == $title && !$book->isAvailable()) {
                $book->returnBook();
                echo "Book '$title' has been returned.<br>";
                return;
            }
        }
        echo "Invalid book title or book is already available.<br>";
    }
}
?>

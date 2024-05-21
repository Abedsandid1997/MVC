<?php

namespace App\Entity;


use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GraficCard.
 */
class BooksTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    

     public function testSetAndGetTitle()
    {
        $book = new Books();
        $book->setTitle('Harry potter');

        $this->assertEquals('Harry potter', $book->getTitle());
    }

    public function testSetAndGetIsbn()
    {
        $book = new Books();
        $book->setIsbn('1234567890123');

        $this->assertEquals('1234567890123', $book->getIsbn());
    }

    public function testSetAndGetAuthor()
    {
        $book = new Books();
        $book->setAuthor('Test Author');

        $this->assertEquals('Test Author', $book->getAuthor());
    }

    public function testSetAndGetImageUrl()
    {
        $book = new Books();
        $book->setImageUrl('https://example.com/image.jpg');

        $this->assertEquals('https://example.com/image.jpg', $book->getImageUrl());
    }

   


}

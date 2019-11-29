<?php

/**
 * How to deal with nested objects?
 * We need to use the PhpDocExtractor.
 */

namespace Serializer\Example6;

require_once 'backend/vendor/autoload.php';

use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Author
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Book[]
     */
    private $books;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * @param Book[] $books
     * @return Author
     */
    public function setBooks(array $books): Author
    {
        $this->books = $books;

        return $this;
    }
}

class Book
{
    /**
     * @var string
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Book
     */
    public function setTitle(string $title): Book
    {
        $this->title = $title;

        return $this;
    }
}

$book1 = new Book();
$book1->setTitle('The Pillars of the Earth');
$book2 = new Book();
$book2->setTitle('Fall of Giants');
$books = [$book1, $book2];

$author = new Author();
$author->setName('Kenneth Martin Follett')
    ->setBooks($books);

echo PHP_EOL;
var_dump($author);
echo PHP_EOL;

$normalizers = [new ObjectNormalizer()];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

$json = <<<JSON
{
    "name":"Kenneth Martin Follett",
    "books":[
        {"title":"The Pillars of the Earth"},
        {"title":"Fall of Giants"}
    ]
}
JSON;

$author = $serializer->deserialize($json, Author::class, JsonEncoder::FORMAT);

echo PHP_EOL;
var_dump($author);
echo PHP_EOL;

$normalizers = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, new PhpDocExtractor())];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

$author = $serializer->deserialize($json, Author::class, JsonEncoder::FORMAT);

echo PHP_EOL;
var_dump($author);
echo PHP_EOL;

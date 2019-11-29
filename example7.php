<?php

/**
 * How to deal with interfaces?
 * We need to use a discrimination map.
 */

namespace Serializer\Example7;

require_once 'backend/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
class_exists(DiscriminatorMap::class);

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

/**
 * @DiscriminatorMap(typeProperty="type", mapping={
 *    "novel"="Serializer\Example7\Novel",
 *    "essay"="Serializer\Example7\Essay"
 * })
 */
interface Book
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return Book
     */
    public function setTitle(string $title): Book;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return Book
     */
    public function setType(string $type): Book;
}

class Novel implements Book
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->type = 'novel';
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $title): Book
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): Book
    {
        $this->type = $type;

        return $this;
    }
}

class Essay implements Book
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->type = 'essay';
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle(string $title): Book
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): Book
    {
        $this->type = $type;

        return $this;
    }
}

$novel = new Novel();
$novel->setTitle('Germinal');
$essay = new Essay();
$essay->setTitle('J\'accuse ...');
$books = [$novel, $essay];

$author = new Author();
$author->setName('Emile Zola')
    ->setBooks($books);

echo PHP_EOL;
var_dump($author);
echo PHP_EOL;

$json = <<<JSON
{
    "name":"Emile Zola",
    "books":[
        {"title":"Germinal", "type":"novel"},
        {"title":"J'accuse ...", "type":"essay"}
    ]
}
JSON;

$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
$extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
$discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);
$normalizers = [
    new ArrayDenormalizer(),
    new ObjectNormalizer($classMetadataFactory, null, null, $extractor, $discriminator)
];

$serializer = new Serializer($normalizers, [new JsonEncoder()]);

$author = $serializer->deserialize($json, Author::class, JsonEncoder::FORMAT);

echo PHP_EOL;
var_dump($author);
echo PHP_EOL;

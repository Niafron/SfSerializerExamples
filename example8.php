<?php

/**
 * Let's make some hydratation.
 */

namespace Serializer\Example8;

require_once 'backend/vendor/autoload.php';

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class Author
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    private $age;

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
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return Author
     */
    public function setAge(int $age): Author
    {
        $this->age = $age;

        return $this;
    }
}

$normalizers = [new GetSetMethodNormalizer()];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

$author = new Author();
$author->setName('Valerio Evangelisti');

echo PHP_EOL;
var_dump($author);
echo PHP_EOL;

$json = <<<JSON
{
    "age": 67
}
JSON;

$updatedAuthor = $serializer->deserialize($json,
    Author::class,
    JsonEncoder::FORMAT,
    [AbstractNormalizer::OBJECT_TO_POPULATE => $author]);

echo PHP_EOL;
var_dump($updatedAuthor);
echo PHP_EOL;


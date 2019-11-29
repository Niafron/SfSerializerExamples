<?php

/**
 * Basic serialization and deserialization operation
 */

namespace Serializer\Example1;

require_once 'backend/vendor/autoload.php';

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
$author->setName('Stephen King')->setAge(72);

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT));
echo PHP_EOL;

$json = <<<JSON
{
    "name": "Dan Simmons",
    "age": 71
}
JSON;

echo PHP_EOL;
var_dump($serializer->deserialize($json, Author::class, JsonEncoder::FORMAT));
echo PHP_EOL;




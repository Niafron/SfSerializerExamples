<?php

/**
 * If I transform a json string into an object, I make a deserialization operation.
 * We can decompose this one in two subs operations : decode and denormalize.
 */

namespace Serializer\Example2;

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

$json = <<<JSON
{
    "name": "Arthur C. Clarke",
    "age": 90
}
JSON;

$data = $serializer->decode($json, JsonEncoder::FORMAT);

echo PHP_EOL;
var_dump($data);
echo PHP_EOL;

echo PHP_EOL;
var_dump($serializer->denormalize($data, Author::class));
echo PHP_EOL;

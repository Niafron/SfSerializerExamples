<?php

/**
 * The first validation of an entity it's the Entity class itself.
 */

namespace Serializer\Example5;

require_once 'backend/vendor/autoload.php';

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
     * @throws \Exception
     */
    public function setAge(int $age): Author
    {
        if ($age > 50) {
            throw new \Exception('To old to write something interesting!');
        }

        $this->age = $age;

        return $this;
    }
}

$normalizers = [new GetSetMethodNormalizer()];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

$json = <<<JSON
{
    "name": "Jean Teulé",
    "age": 66
}
JSON;

echo PHP_EOL;
var_dump($serializer->deserialize($json, Author::class, JsonEncoder::FORMAT));
echo PHP_EOL;

//ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT;


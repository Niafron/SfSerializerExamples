<?php

/**
 * We can filter the properties that we want to serialize or deserialize.
 */

namespace Serializer\Example4;

require_once 'backend/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
class_exists(Groups::class);

class Author
{
    /**
     * @var string
     * @Groups({"author:light", "author:basic"})
     */
    private $name;

    /**
     * @var int
     * @Groups("author:basic")
     */
    private $age;

    /**
     * @var string
     */
    private $nationality;

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

    /**
     * @return string
     */
    public function getNationality(): string
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     * @return Author
     */
    public function setNationality(string $nationality): Author
    {
        $this->nationality = $nationality;

        return $this;
    }
}

$author = new Author();
$author->setName('Eric-Emmanuel Schmitt');
$author->setAge(59);
$author->setNationality('french');

$normalizers = [new GetSetMethodNormalizer()];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT));
echo PHP_EOL;

$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
$normalizers = [new ObjectNormalizer($classMetadataFactory)];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT, ['groups' => ['author:light']]));
echo PHP_EOL;

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT, ['groups' => ['author:basic']]));
echo PHP_EOL;

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT, ['groups' => ['author:light', 'author:basic']]));
echo PHP_EOL;

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT, [AbstractNormalizer::ATTRIBUTES => ['nationality']]));
echo PHP_EOL;

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['nationality', 'age']]));
echo PHP_EOL;

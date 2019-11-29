<?php

/**
 * We can use several specific normalizers like the DateTimeNormalizer
 */

namespace Serializer\Example3;

require_once 'backend/vendor/autoload.php';

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Author
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $birthDate;

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
     * @return \DateTime
     */
    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     * @return Author
     */
    public function setBirthDate(\DateTime $birthDate): Author
    {
        $this->birthDate = $birthDate;

        return $this;
    }
}

$author = new Author();
$author->setName('Philip K. Dick');
$author->setBirthDate(\DateTime::createFromFormat('d/m/Y', '16/12/1928'));

$normalizers = [new GetSetMethodNormalizer()];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT));
echo PHP_EOL;

$normalizers = [new GetSetMethodNormalizer(), new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT));
echo PHP_EOL;

$normalizers = [new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y']), new GetSetMethodNormalizer()];
$encoders = [new JsonEncoder()];
$serializer = new Serializer($normalizers, $encoders);

echo PHP_EOL;
var_dump($serializer->serialize($author, JsonEncoder::FORMAT));
echo PHP_EOL;

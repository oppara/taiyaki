<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class Taiyaki
{
    private $anko;
    private $size;

    public function __construct($anko, $size)
    {
        $this->anko = $anko;
        $this->size = $size;
    }

    public function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->{$name};
        }
    }

}

class TaiyakiTest extends TestCase
{
    /**
     * @test
     * @dataProvider taiyakiProvider
     */
    public function taiyaki($anko, $size)
    {
        $taiyaki = new Taiyaki($anko, $size);
        $this->assertSame($anko, $taiyaki->anko);
        $this->assertSame($size, $taiyaki->size);
    }

    public function taiyakiProvider()
    {
        return [
            ['あずき', 'ふつう'],
            ['白あん', '大きめ'],
        ];
    }
}


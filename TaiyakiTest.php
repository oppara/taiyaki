<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class Taiyaki
{
    private $anko;

    public function __construct($anko)
    {
        $this->anko = $anko;
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
    public function taiyaki($anko)
    {
        $taiyaki = new Taiyaki($anko);
        $this->assertSame($anko, $taiyaki->anko);
    }

    public function taiyakiProvider()
    {
        return [
            ['あずき'],
            ['白あん'],
        ];
    }
}


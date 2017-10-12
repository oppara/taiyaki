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

    public function __toString()
    {
        return sprintf('あんこ: %s, 大きさ: %s', $this->anko, $this->size);
    }

}

class TaiyakiTest extends TestCase
{
    /**
     * @test
     * @dataProvider taiyakiProvider
     */
    public function taiyaki($anko, $size, $str)
    {
        $taiyaki = new Taiyaki($anko, $size);
        $this->assertSame($anko, $taiyaki->anko);
        $this->assertSame($size, $taiyaki->size);
        $this->assertSame($str, (string) $taiyaki);
    }

    public function taiyakiProvider()
    {
        return [
            ['あずき', 'ふつう', 'あんこ: あずき, 大きさ: ふつう'],
            ['白あん', '大きめ', 'あんこ: 白あん, 大きさ: 大きめ'],
        ];
    }
}


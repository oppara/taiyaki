<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class Taiyaki
{
    private $anko;
    private $size;
    private $price;
    private $base_price = 100;

    public function __construct($anko, $size)
    {
        $this->anko = $anko;
        $this->size = $size;
        $this->setPrice();
    }

    private function setPrice()
    {
        $this->price = $this->base_price;
        if ($this->anko == '白あん') {
            $this->price += 30;
        }
        if ($this->size == '大きめ') {
            $this->price += 50;
        }
    }

    public function __get($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->{$name};
        }
    }

    public function __toString()
    {
        return sprintf('あんこ: %s, 大きさ: %s, %d円', $this->anko, $this->size, $this->price);
    }

}

class TaiyakiTest extends TestCase
{
    /**
     * @test
     * @dataProvider taiyakiProvider
     */
    public function taiyaki($anko, $size, $price, $str)
    {
        $taiyaki = new Taiyaki($anko, $size);
        $this->assertSame($anko, $taiyaki->anko);
        $this->assertSame($size, $taiyaki->size);
        $this->assertSame($price, $taiyaki->price);
        $this->assertSame($str, (string) $taiyaki);
    }

    public function taiyakiProvider()
    {
        return [
            ['あずき', 'ふつう', 100, 'あんこ: あずき, 大きさ: ふつう, 100円'],
            ['白あん', '大きめ', 180, 'あんこ: 白あん, 大きさ: 大きめ, 180円'],
        ];
    }
}


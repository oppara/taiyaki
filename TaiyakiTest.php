<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Cake\Chronos\Date;

class Taiyaki
{
    private $base_price = 100;
    private $data;

    public function __construct($anko, $size)
    {
        $this->data['anko'] = $anko;
        $this->data['size'] = $size;
        $this->setPrice();
        $this->setDate();
    }

    public function isExpired($today = null)
    {
        if (is_null($today)) {
            $today = Date::today();
        }

        return $this->data['expire_on']->lt($today);
    }

    private function setPrice()
    {
        $this->data['price'] = $this->base_price;
        if ($this->data['anko'] == '白あん') {
            $this->data['price'] += 30;
        }

        if ($this->data['size'] == '大きめ') {
            $this->data['price'] += 50;
        }
    }

    private function setDate()
    {
        $today = Date::today();
        $this->data['produced_on'] = $today;
        $this->data['expire_on'] = $today->addDays(3);
    }

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function __toString()
    {
        $args = [
            $this->data['anko'],
            $this->data['size'],
            $this->data['price'],
        ];

        return vsprintf('あんこ: %s, 大きさ: %s, %d円', $args);
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

    /**
     * @test
     */
    public function expired()
    {
        $taiyaki = new Taiyaki('あずき', 'ふつう');

        // 製造日 = システム日付
        $this->assertSame((string) Date::today(), (string) $taiyaki->produced_on);

        // 賞味期限 = 製造日 + 3日
        $expected = (string) Date::today()->addDays(3);
        $this->assertSame($expected, (string) $taiyaki->expire_on);

        // 当日なら食べられる
        $this->assertFalse($taiyaki->isExpired());

        // 3日目まで食べられる
        $this->assertFalse($taiyaki->isExpired(Date::today()->addDays(3)));

        // 4日目を過ぎると食べられない
        $this->assertTrue($taiyaki->isExpired(Date::today()->addDays(4)));
    }

}


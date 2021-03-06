<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Cake\Chronos\Date;

class Taiyaki
{
    const EXPIRE_DATE = 3;

    private $data;
    private $attr_readers = [
        'anko',
        'size',
        'price',
        'produced_on',
        'expire_on',
    ];

    public function __construct($anko, $size, $price = 100)
    {
        $this->setAnko($anko);
        $this->setSize($size);
        $this->setPrice($price);
        $this->setDate();
    }

    private function setAnko($anko)
    {
        $this->data['anko'] = $anko;
    }

    private function setSize($size)
    {
        $this->data['size'] = $size;
    }

    private function setPrice($price)
    {
        $this->data['price'] = $price;
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
        $this->data['expire_on'] = $today->addDays(self::EXPIRE_DATE);
    }

    public function isExpired($today = null)
    {
        if (is_null($today)) {
            $today = Date::today();
        }

        return $this->data['expire_on']->lt($today);
    }

    public function __get($name)
    {
        if (!in_array($name, $this->attr_readers)) {
            return;
        }

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
        $expected = (string) Date::today()->addDays(Taiyaki::EXPIRE_DATE);
        $this->assertSame($expected, (string) $taiyaki->expire_on);

        // 当日なら食べられる
        $this->assertFalse($taiyaki->isExpired());

        // 3日目まで食べられる
        $days = Taiyaki::EXPIRE_DATE;
        $this->assertFalse($taiyaki->isExpired(Date::today()->addDays($days)));

        // 4日目を過ぎると食べられない
        $days = Taiyaki::EXPIRE_DATE + 1;
        $this->assertTrue($taiyaki->isExpired(Date::today()->addDays($days)));
    }

}


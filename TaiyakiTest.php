<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class Taiyaki
{

}

class TaiyakiTest extends TestCase
{
    /**
     * @test
     */
    public function taiyaki()
    {
        $this->assertInstanceOf(Taiyaki::class, new Taiyaki);
    }

}


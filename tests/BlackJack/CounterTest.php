<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GraficCard.
 */
class CounterTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreateCounter(): void
    {


        $counter = new Counter();
        $this->assertInstanceOf("\App\BlackJack\Counter", $counter);



    }






    public function testUpdateStatistics(): void
    {

        $counter = new Counter();


        $counter->updateStatistics("2");
        $counter->updateStatistics("2");
        $counter->updateStatistics("Ess");
        $counter->updateStatistics("Ess");

        $res = $counter->getStatistics();
        $exp = ["2" => 2, "Ess" => 2];
        $this->assertEquals($exp, $res);
        $this->assertNotEmpty($res);
    }

    public function testGetRunningCount(): void
    {

        $counter = new Counter();


        $counter->updateStatistics("2");
        $counter->updateStatistics("2");
        $counter->updateStatistics("2");
        $counter->updateStatistics("10");
        $counter->updateStatistics("Kung");

        $res = $counter->getRunningCount();
        $exp = 1;
        $this->assertEquals($exp, $res);
        $this->assertNotEmpty($res);
        $this->assertIsInt($res);
    }

    public function testProbabilityOfBusting(): void
    {

        $counter = new Counter();


        $counter->updateStatistics("2");
        $counter->updateStatistics("2");
        $counter->updateStatistics("2");
        $counter->updateStatistics("10");
        $counter->updateStatistics("Kung");
        $counter->updateStatistics("7");

        $counter->getRunningCount();
        $res = $counter->probabilityOfBusting(20);
        $exp = 100.0;
        $this->assertEquals($exp, $res);
        $this->assertNotEmpty($res);
        $this->assertIsFloat($res);

    }

}

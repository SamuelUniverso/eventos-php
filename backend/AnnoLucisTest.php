<?php

use PHPUnit\Framework\TestCase;

include "./vendor/autoload.php";

///  php .\vendor\bin\phpunit AnnoLucisTest.php

class AnnoLucisTest extends TestCase
{
    public function testCalculaAnnoLucis_success()
    {
        $AnnoDominiBirthYear = 1997;

        $factor = 4000;
        
        $AnnoLucisBirthYear = $AnnoDominiBirthYear + $factor;

        $this->assertEquals(5997, $AnnoLucisBirthYear);
    }

    public function testCalculaAnnoLucis_fail()
    {
        $AnnoDominiBirthYear = 1997;

        $factor = 4001;
        
        $AnnoLucisBirthYear = $AnnoDominiBirthYear + $factor;

        $this->assertNotEquals(5997, $AnnoLucisBirthYear);
    }
}

<?php

use PHPUnit\Framework\TestCase;

include "./vendor/autoload.php";

///  php .\vendor\bin\phpunit AnnoDominiTest.php

class AnnoDominiTest extends TestCase
{
    public function testCalculaAnnoDomini_success()
    {
        $AnnoLucisBirthYear = 5997;

        $factor = 4000;
        
        $AnnoDominiBirthYear = $AnnoLucisBirthYear - $factor;

        $this->assertEquals(1997, $AnnoDominiBirthYear);
    }

    public function testCalculaAnnoDomini_fail()
    {
        $AnnoLucisBirthYear = 5997;

        $factor = 4001;
        
        $AnnoDominiBirthYear = $AnnoLucisBirthYear - $factor;

        $this->assertEquals(1997, $AnnoDominiBirthYear);
    }
}

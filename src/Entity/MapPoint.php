<?php

namespace App\Entity;


class MapPoint
{
    private $longitude;

    private $latitude;

    public function __construct($latitude,$longitude){
        $this->$latitude = $latitude;
        $this->$longitude = $longitude;
    }
}

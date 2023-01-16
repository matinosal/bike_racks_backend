<?php

namespace App\Entity;
//TODO przerobić na klasę korzystającą z refleksji
interface JsonSerializeObject{
    public function toJson();
}
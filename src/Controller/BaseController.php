<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends AbstractController{

    protected function getJsonSerializer(){
        return new Serializer([new ObjectNormalizer()],[new JsonEncoder()]);
    }
    
    protected function validateFields($data,$requiredFields){
        $errors = [];
        foreach($requiredFields as $field){
            if(!isset($data[$field]) && empty($data[$field]))
                $errors[] = $field;
        }
        return $errors;
    }
}
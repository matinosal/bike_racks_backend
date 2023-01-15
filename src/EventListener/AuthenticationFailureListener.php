<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationFailureListener{
    /**
     * @param AuthenticationFailureEvent  $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse('Bad credentials, please verify that your email/password are correctly set', JsonResponse::HTTP_UNAUTHORIZED);
        $data['data'] = false;
        $response->setData($data);
        $event->setResponse($response);
    }
}

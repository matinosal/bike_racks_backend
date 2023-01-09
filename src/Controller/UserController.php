<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('add',methods:"POST", name:"add_user")]
    public function addUser(ManagerRegistry $doctrine,Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $data = $request->toArray();
        if(!isset($data['user'])){
            return $this->json([
                'message' => "invalid fields",
                'data'    => "false"
            ],400);
        }

        $userData = $data['user'];
        $errors = $this->validateFields(
            $userData,
            ["username","password","email"]
        );
        
        if(!empty($errors)){
            $message = "invalid fields";

            foreach($errors as $error){
                $message .= " $error";
            }

            return $this->json([
                'message' => "$message",
                'data'    => "false"
            ],400);
        }

        $userManager = $doctrine->getManager();
        $user = new User();
        $user->setLogin($userData['username']);
        $user->setEmail($userData['email']);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $userData['password']
            )
        );

        $userManager->persist($user);

        $userManager->flush();

        return $this->json([
            'data'  => true
        ]);
    }

    private function validateFields($data,$requiredFields){
        $errors = [];
        foreach($requiredFields as $field){
            if(!isset($data[$field]) && empty($data[$field]))
                $errors[] = $field;
        }
        return $errors;
    }
}
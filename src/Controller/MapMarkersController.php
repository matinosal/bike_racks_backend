<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\MapMarker;
use App\Entity\UserVisitedMarkers;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/api/markers')]
class MapMarkersController extends BaseController
{
    #[Route('/location', methods:["POST"], name:"get_marker_by_location")]
    public function getMarkersByLocation(ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $data = $request->toArray();
        if(!isset($data['points']) || count($data['points']) != 2 ){
            return $this->json([
                'message' => "invalid fields",
                'markers' => [],
            ], 400);
        }

        $mapMarkersRepository = $doctrine->getManager()->getRepository(MapMarker::class);
        $markers = $mapMarkersRepository->getMarkers($data['points'][0], $data['points'][1]);

        return $this->json([
            'code'      => 200,
            'markers'   => $markers
        ]);
    }

    #[Route('/visit', methods:["POST"], name:"visit_marker")]
    public function visitedMarker(Security $security,ManagerRegistry $doctrine,Request $request)
    {
        $data = $request->toArray();
        $errors = $this->validateFields(
            $data,
            ["marker_id"]
        );
        //TODO przeniesc to do osobnej metody w basecontroller
        if(!empty($errors)){
            $message = "invalid fields";

            foreach($errors as $error){
                $message .= " $error";
            }

            return $this->json([
                'message' => "$message",
                'data'    => false
            ],400);
        }
        $manager = $doctrine->getManager();
        $visit = new UserVisitedMarkers();
        $visit->setMapMarkerId($data['marker_id']);
        $visit->setUserId($security->getUser()->getId());
        $manager->persist($visit);
        $manager->flush();

        return $this->json([
            'data'  => true
        ]); 
    }

    #[Route('/{id}', methods:["POST","GET"], name:"get_marker_info")]
    public function getMarkerInfo(Security $security,ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $data['marker'] = $doctrine->getManager()->getRepository(MapMarker::class)->find($id);
        $token = $security->getToken();
        $user = $security->getUser();
        if (!empty($token) && !empty($user)) {
            $userId = $user->getId();
            $result = $doctrine->getManager()->getRepository(UserVisitedMarkers::class)->visited($userId,$id);
            $data['visited'] = $result ? true : false;
        }
        return $this->json($data);
    }
    #[Route('/', methods:["POST"], name:"add_marker")]
    public function newMarker(Security $security,ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger): JsonResponse
    {
        $data = $request->request->all();
        $data['image'] = $request->files->get('image');

        $errors = $this->validateFields(
            $data,
            ["description","latitude","longitude","image","imageName"]
        );
        //TODO przeniesc to do osobnej metody w basecontroller
        if(!empty($errors)){
            $message = "invalid fields";

            foreach($errors as $error){
                $message .= " $error";
            }

            return $this->json([
                'message' => "$message",
                'data'    => false
            ],400);
        }

                
        $safeFilename = $slugger->slug($data['imageName']);
        $newFilename =  $safeFilename.'.'.$data['image']->guessExtension();
        
        try{
            $data['image']->move(
                $this->getParameter('upload_dir'),
                $newFilename
            );
        } catch(FileException $e){
            return $this->json([
                'message' => "file upload error: ".$e->getMessage(),
                'data'    => false
            ],400);
        }

        $user =  $security->getUser();
        $marker = new MapMarker();
        $marker->setLatitude($data['latitude']);
        $marker->setLongitude($data['longitude']);
        $marker->setDescription($data['description']);
        $marker->setAddedBy($user->getId());
        $marker->setImgSrc($newFilename);

        $manager = $doctrine->getManager();
        $manager->persist($marker);
        $manager->flush();

        return $this->json([
            'data'  => true
        ]);
    }


}

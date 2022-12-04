<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\MapMarker;

#[Route('/markers')]
class MapMarkersController extends AbstractController
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

        $markersAsJson = [];
        foreach($markers as $obj){
            $markersAsJson[] = $obj->toJson();
        }

        return $this->json([
            'code'      => 200,
            'markers'   => $markersAsJson
        ]);
    }

}

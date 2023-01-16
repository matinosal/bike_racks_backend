<?php

namespace App\Entity;

use App\Repository\UserVisitedMarkersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserVisitedMarkersRepository::class)]
class UserVisitedMarkers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $map_marker_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getMapMarkerId(): ?int
    {
        return $this->map_marker_id;
    }

    public function setMapMarkerId(int $map_marker_id): self
    {
        $this->map_marker_id = $map_marker_id;

        return $this;
    }

}

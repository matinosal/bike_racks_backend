<?php

namespace App\Entity;

use App\Repository\MapMarkerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MapMarkerRepository::class)]
class MapMarker implements JsonSerializeObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function toJsonQuickInfo()
    {
        return [
            'id'            => $this->id,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
        ];
    }

    public function toJson()
    {
        return [
            'id'            => $this->id,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'description'   => $this->description
        ];
    }
}

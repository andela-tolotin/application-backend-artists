<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 */
class Song
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="float", length=10)
     */
    private $length;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Album", inversedBy="songs")
     */
    private $Album;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLength(): ?int
    {
        // Convert length to minutes
        return (int) ($this->length / 60);
    }

    public function setLength(string $length): self
    {
        // Transform the length to seconds
        list($min, $sec) = explode(':', $length);

        $this->length = (int) (($min * 60) + $sec);

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->Album;
    }

    public function setAlbum(?Album $Album): self
    {
        $this->Album = $Album;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\RssRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("rss", uniqueConstraints={@ORM\UniqueConstraint(name="link_id", columns={"link_id"})}, indexes={@ORM\Index(name="link_id_idx", columns={"link_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\RssRepository")
 */
class Rss
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $link_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $votes;

    /**
     * @ORM\Column(type="integer")
     */
    private $karma;

    /**
     * @ORM\Column(type="integer")
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pub_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = RssRepository::STATUS_PUBLISHED;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkId(): ?int
    {
        return $this->link_id;
    }

    public function setLinkId(int $link_id): self
    {
        $this->link_id = $link_id;

        return $this;
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

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getKarma(): ?int
    {
        return $this->karma;
    }

    public function setKarma(int $karma): self
    {
        $this->karma = $karma;

        return $this;
    }

    public function getComments(): ?int
    {
        return $this->comments;
    }

    public function setComments(int $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getPubDate(): ?\DateTimeInterface
    {
        return $this->pub_date;
    }

    public function setPubDate(\DateTimeInterface $pub_date): self
    {
        $this->pub_date = $pub_date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
}

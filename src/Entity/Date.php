<?php

namespace App\Entity;

use App\Repository\DateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateRepository::class)
 */
class Date
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Website::class, inversedBy="dates")
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $connectionToUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $DisconnectionFromUrl;


    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="dates")
     */
    private $user;

    public function __construct()
    {
        $this->website = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Website[]
     */
    public function getWebsite(): Collection
    {
        return $this->website;
    }

    public function addWebsite(Website $website): self
    {
        if (!$this->website->contains($website)) {
            $this->website[] = $website;
        }

        return $this;
    }

    public function removeWebsite(Website $website): self
    {
        $this->website->removeElement($website);

        return $this;
    }

    public function getConnectionToUrl(): ?string
    {
        return $this->connectionToUrl;
    }

    public function setConnectionToUrl(string $connectionToUrl): self
    {
        $this->connectionToUrl = $connectionToUrl;

        return $this;
    }

    public function getDisconnectionFromUrl(): ?string
    {
        return $this->DisconnectionFromUrl;
    }

    public function setDisconnectionFromUrl(string $DisconnectionFromUrl): self
    {
        $this->DisconnectionFromUrl = $DisconnectionFromUrl;

        return $this;
    }


    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }
}

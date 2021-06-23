<?php

namespace App\Entity;

use App\Repository\WebsiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebsiteRepository::class)
 */
class Website
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="website")
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $page_view;

    /**
     * @ORM\ManyToMany(targetEntity=Date::class, mappedBy="website")
     */
    private $dates;

    public function __construct()
    {
        $this->website = new ArrayCollection();
        $this->dates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getWebsite(): Collection
    {
        return $this->website;
    }

    public function addWebsite(User $website): self
    {
        if (!$this->website->contains($website)) {
            $this->website[] = $website;
            $website->setWebsite($this);
        }

        return $this;
    }

    public function removeWebsite(User $website): self
    {
        if ($this->website->removeElement($website)) {
            // set the owning side to null (unless already changed)
            if ($website->getWebsite() === $this) {
                $website->setWebsite(null);
            }
        }

        return $this;
    }

    public function getUrlWebsite(): ?string
    {
        return $this->url_website;
    }

    public function setUrlWebsite(string $url_website): self
    {
        $this->url_website = $url_website;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getPageView(): ?string
    {
        return $this->page_view;
    }

    public function setPageView(string $page_view): self
    {
        $this->page_view = $page_view;

        return $this;
    }

    /**
     * @return Collection|Date[]
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }

    public function addDate(Date $date): self
    {
        if (!$this->dates->contains($date)) {
            $this->dates[] = $date;
            $date->addWebsite($this);
        }

        return $this;
    }

    public function removeDate(Date $date): self
    {
        if ($this->dates->removeElement($date)) {
            $date->removeWebsite($this);
        }

        return $this;
    }
}

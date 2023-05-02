<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'status_id', targetEntity: notifications::class)]
    private Collection $Notification;

    public function __construct()
    {
        $this->Notification = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, notifications>
     */
    public function getNotification(): Collection
    {
        return $this->Notification;
    }

    public function addNotification(notifications $notification): self
    {
        if (!$this->Notification->contains($notification)) {
            $this->Notification->add($notification);
            $notification->setStatusId($this);
        }

        return $this;
    }

    public function removeNotification(notifications $notification): self
    {
        if ($this->Notification->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getStatusId() === $this) {
                $notification->setStatusId(null);
            }
        }

        return $this;
    }
}

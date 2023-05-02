<?php

namespace App\Entity;

use App\Repository\FolderNotificationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FolderNotificationsRepository::class)]
class FolderNotifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $folder_id = null;

    #[ORM\Column]
    private ?int $notification_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFolderId(): ?int
    {
        return $this->folder_id;
    }

    public function setFolderId(int $folder_id): self
    {
        $this->folder_id = $folder_id;

        return $this;
    }

    public function getNotificationId(): ?int
    {
        return $this->notification_id;
    }

    public function setNotificationId(int $notification_id): self
    {
        $this->notification_id = $notification_id;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\NotificationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups("getNotification")]
    private ?int $user_id = null;

    #[ORM\Column(nullable: true)]
    #[Groups("getNotification")]
    private ?int $services_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getNotification")]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getNotification")]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups("getNotification")]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column]
    #[Groups("getNotification")]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("getNotification")]
    private ?string $url = null;

    #[ORM\Column(nullable: true)]
    private ?int $app = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getServicesId(): ?int
    {
        return $this->services_id;
    }

    public function setServicesId(?int $services_id): self
    {
        $this->services_id = $services_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(?\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getApp(): ?int
    {
        return $this->app;
    }

    public function setApp(?int $app): self
    {
        $this->app = $app;

        return $this;
    }
}

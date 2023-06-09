<?php

namespace App\Controller;

use App\class\ApiAuthenticator;
use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Services;


class ApiNotificationController extends AbstractController
{
    private $serializer;
    private $entityManager;
    private $urlGenerator;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    // Récupérer toutes les notifications
    #[Route('/api/notifications', name: 'notification', methods: ['GET'])]
    public function getNotificationList(NotificationsRepository $notificationsRepository): JsonResponse
    {

        // AUTH TOKKEN
        //$authenticator= new ApiAuthenticator();
        //$token = $authenticator->AuthToken();

        //$notificationList = $notificationsRepository->findAll();
        //$jsonNotificationList = $this->serializer->serialize($notificationList, 'json');

        return new JsonResponse($jsonNotificationList, Response::HTTP_OK, [], true);
    }

    // Créer une nouvelle notification
    #[Route('/api/notifications', name: "createNotification", methods: ['POST'])]
    public function createNotification(Request $request): JsonResponse
    {
        $jsonData = $request->getContent();
        $notification = $this->serializer->deserialize($jsonData, Notifications::class, 'json');

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        $jsonNotification = $this->serializer->serialize($notification, 'json');
        $location = $this->urlGenerator->generate('detailNotification', ['id' => $notification->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonNotification, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    // Mettre à jour une notification
    #[Route('/api/notifications/{id}', name: "updateNotification", methods: ['PUT'])]
    public function updateNotification(Request $request, Notifications $notification): JsonResponse
    {
        $jsonData = $request->getContent();
        $updatedNotification = $this->serializer->deserialize($jsonData, Notifications::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $notification]);

        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // Supprimer une notification
    #[Route('/api/notifications/{id}', name: 'deleteNotification', methods: ['DELETE'])]
    public function deleteNotification(Notifications $notification): JsonResponse
    {
        $this->entityManager->remove($notification);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // Récupérer le détail d'une notification par ID
    #[Route('/api/notifications/{id}', name: 'detailNotification', methods: ['GET'])]
    public function getDetailNotification(Notifications $notification): JsonResponse
    {
        $jsonNotification = $this->serializer->serialize($notification, 'json');

        return new JsonResponse($jsonNotification, Response::HTTP_OK, [], true);
    }

    // Récupérer les notifications d'un utilisateur par User_ID
    #[Route('/api/notifications/user/{userId}', name: 'notificationsByUser', methods: ['GET'])]
    public function getNotificationsByUser($userId, NotificationsRepository $notificationsRepository): JsonResponse
    {
        $notifications = $notificationsRepository->findBy(['user_id' => $userId]);
        $jsonNotifications = $this->serializer->serialize($notifications, 'json');

        return new JsonResponse($jsonNotifications, Response::HTTP_OK, ['Content-Type' => 'application/json'], true);
    }
}

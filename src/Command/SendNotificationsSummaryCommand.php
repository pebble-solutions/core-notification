<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Notifications;
use App\Entity\Users;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputOption;


#[AsCommand(
    name: 'app:send-notifications-summary',
    description: 'Commande pour envoyer mail automatique',
)]
class SendNotificationsSummaryCommand extends Command
{
    use LockableTrait;

    protected static $defaultName = 'app:send-notifications-summary';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock('send-notifications-summary')) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        // Récupération des notifications depuis la base de données
        $notifications = $this->entityManager->getRepository(Notifications::class)->findForToday();

        // Tri des notifications par service
        $notificationsByService = [];
        foreach ($notifications as $notification) {
            $serviceName = $notification->getService()->getName();

            // Regarde si elle n'existe pas déjà
            if (!isset($notificationsByService[$serviceName])) {
                $notificationsByService[$serviceName] = [];
            }

            // Ajouter si non existante
            $notificationsByService[$serviceName][] = $notification;
        }

        // recup user

        $users = $this->entityManager->getRepository(Users::class)->findAll();

        foreach ($users as $user) {
            $settings = $user->getSettingsNotification();

            // Check si les utilisateurs on activer l'envois par mail
            if ($settings->getReceiveEmail() && $settings->isTodayEmail()) {
                $message = (new \Swift_Message('Récapitulatif de vos notifications'))
                    ->setFrom('noreply@example.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $content = $this->renderView(
                            'mail/render.html.twig',
                            ['notificationsByService' => $notificationsByService]

                        ),
                        'text/html'
                    );

                // Afficher le contenu HTML dans la sortie de la commande Symfony
                $output->writeln($content);
            }
        }

        $this->release();

        return Command::SUCCESS;
    }
}

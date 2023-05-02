<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:send-notifications-summary',
    description: 'Commande pour envoyer mail automatique',
)]
class SendNotificationsSummaryCommand extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupération des notifications depuis la base de données
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findForToday();

        // Tri des notifications par service
        $notificationsByService = [];
        foreach ($notifications as $notification) {
            $serviceName = $notification->getService()->getName();
            if (!isset($notificationsByService[$serviceName])) {
                $notificationsByService[$serviceName] = [];
            }
            $notificationsByService[$serviceName][] = $notification;
        }

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $settings = $user->getSettingsNotification();
            if ($settings->getReceiveEmail() && $settings->isTodayEmail()) {
                $message = (new \Swift_Message('Récapitulatif de vos notifications'))
                    ->setFrom('noreply@example.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/notification_summary.html.twig',
                            ['notificationsByService' => $notificationsByService]
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
        }
        return Command::SUCCESS;
    }
}
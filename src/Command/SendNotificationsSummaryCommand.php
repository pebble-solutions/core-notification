<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Notifications;
use App\Entity\Users;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsCommand(
    name: 'app:send-notifications-summary',
    description: 'Commande pour envoyer mail automatique',
)]
class SendNotificationsSummaryCommand extends Command
{
    protected static $defaultName = 'app:send-notifications-summary';

    use LockableTrait;

    private EntityManagerInterface $entityManager;
    private Environment $twig;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig)
    {

        $this->entityManager = $entityManager;
        $this->twig = $twig;
        parent::__construct();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (!$this->lock('send-notifications-summary')) {
            $output->writeln('The command is already running in aoutput10nother process.');

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

        if (!empty($users)) {
            foreach ($users as $user) {
                $settings = $user->getSettingsNotification();

                // Check si les utilisateurs ont activé l'envoi par mail
                if ($settings->getReceiveEmail() && $settings->isTodayEmail()) {
                    $content = $this->twig->render(
                        'mail/render.html.twig',
                        ['notificationsByService' => $notificationsByService]
                    );

                    // Écrire le contenu HTML dans un fichier nommé "output.html"
                    file_put_contents('output10.html', $content);
                }
            }
        } else {
            $content = $this->twig->render(
                'mail/render.html.twig',
                ['notificationsByService' => $notificationsByService]
            );

            // Écrire le contenu HTML dans un fichier nommé "output5.html"
            file_put_contents('output5.html', $content);
        }

        $this->release();

        return Command::SUCCESS;
    }
}

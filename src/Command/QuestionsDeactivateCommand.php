<?php

namespace App\Command;

use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class QuestionsDeactivateCommand extends Command
{
    protected static $defaultName = 'app:questions:deactivate';

    private $em;
    private $questionRepository;

    public function __construct(EntityManagerInterface $em, QuestionRepository $questionRepository)
    {
        parent::__construct();
        $this->em = $em;
        $this->questionRepository = $questionRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Désactive toutes les questions sans activité depuis plus de 7 jours')
            ->addOption('days', 'd', InputOption::VALUE_REQUIRED, 'Âge des questions à désactiver', 7)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // On récupère la valuer de l'option --days
        // Si elle n'est pas définie, sa valeur est 7 (la valeur par défaut définie lors de la déclation de l'option ligne 32)
        $days = $input->getOption('days');

        // Il y a plusieurs solutions pour modifier la propriété $active de toute sles questions concernées
        // Commençons par lea méthode complètement en PHP
        // Il faudrait aller chercher toutes les questions dont la dernière activité a plus de 7 jours et dont la valeur active est à true
        $questions = $this->questionRepository->findAllToDeactivate($days);

        // On va boucler sur les questions et changer la valeur de $active
        foreach ($questions as $question) {
            $question->setActive(false);
        }
        
        // On flushe
        $this->em->flush();

        // On peut aussi utiliser une méthode du repository qui faittout le travail
        // Attention, elle doit être seule dans la commande
        // Il est inutile de la faire cohabiter avec l'autre solution
        // $this->questionRepository->deactivateOldQuestions();

        // On affiche le message de la victoire !
        $io = new SymfonyStyle($input, $output);
        $io->success('Toutes les questions de plus de 7 jours sont désactivées !');

        return 0;
    }
}

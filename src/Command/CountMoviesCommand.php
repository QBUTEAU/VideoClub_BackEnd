<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:count-movies',
    description: 'Returns the number of movies in the database',
)]
class CountMoviesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        // Pas besoin d'arguments ni d'options spécifiques dans cet exemple
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupération du nombre de films
        $movieCount = $this->entityManager->getRepository('App\Entity\Movie')->count([]);

        // Affichage du résultat
        $io->success(sprintf('Nombre de films dans la BDD : %d', $movieCount));

        return Command::SUCCESS;
    }
}

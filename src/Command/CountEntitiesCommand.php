<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface; // Doctrine pour la gestion des entités

#[AsCommand(
    name: 'count-entities',
    description: 'Count movies, actors, and categories',
)]
class CountEntitiesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('movies', null, InputOption::VALUE_NONE, 'Count the number of movies')
            ->addOption('actors', null, InputOption::VALUE_NONE, 'Count the number of actors')
            ->addOption('categories', null, InputOption::VALUE_NONE, 'Count the number of categories');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $showMovies = $input->getOption('movies');
        $showActors = $input->getOption('actors');
        $showCategories = $input->getOption('categories');

        if (!$showMovies && !$showActors && !$showCategories) {
            // Aucun argument spécifique : affiche tout
            $showMovies = $showActors = $showCategories = true;
        }

        if ($showMovies) {
            $count = $this->countEntities('App\Entity\Movie');
            $io->success(sprintf('Nombre de films : %d', $count));
        }

        if ($showActors) {
            $count = $this->countEntities('App\Entity\Actor');
            $io->success(sprintf('Nombre d\'acteurs : %d', $count));
        }

        if ($showCategories) {
            $count = $this->countEntities('App\Entity\Category');
            $io->success(sprintf('Nombre de genres : %d', $count));
        }

        return Command::SUCCESS;
    }

    private function countEntities(string $entityClass): int
    {
        return $this->entityManager->getRepository($entityClass)->count([]);
    }
}

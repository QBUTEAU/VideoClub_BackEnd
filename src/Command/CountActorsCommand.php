<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:count-actors',
    description: 'Returns the number of actors in the database',
)]
class CountActorsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupération du nombre d'acteurs
        $query = $this->entityManager->createQuery('SELECT COUNT(a.id) FROM App\Entity\Actor a');
        $count = $query->getSingleScalarResult();

        $io->success(sprintf('Nombre d\'acteurs dans la BDD : %d', $count));

        return Command::SUCCESS;
    }
}

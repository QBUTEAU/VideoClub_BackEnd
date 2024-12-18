<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

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
            ->addArgument('type', InputArgument::REQUIRED, 'The type of data to display (movies, actors, categories)')
            ->addOption('details', null, InputOption::VALUE_NONE, 'Show detailed information')
            ->addOption('log-file', null, InputOption::VALUE_OPTIONAL, 'Specify a custom log file', '/var/log/command.log')
            ->addArgument('category', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Specify the category or categories to get related movies');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupération de l'argument obligatoire
        $type = $input->getArgument('type');

        // Vérification des options
        $showDetails = $input->getOption('details');
        $logFile = $input->getOption('log-file');
        $categories = $input->getArgument('category');  // Get categories argument

        // Affichage du type demandé
        $io->title(sprintf('Comptage des valeurs comprises dans : %s', ucfirst($type)));

        switch ($type) {
            case 'movies':
                $count = $this->countEntities('App\Entity\Movie');
                $io->success(sprintf('Nombre de films : %d', $count));
                if ($showDetails) {
                    $this->showMovieDetails($io);
                }
                break;

            case 'actors':
                $count = $this->countEntities('App\Entity\Actor');
                $io->success(sprintf('Nombre d\'acteurs : %d', $count));
                if ($showDetails) {
                    $this->showActorDetails($io);
                }
                break;

            case 'categories':
                // Get the number of categories
                $categoriesData = $this->countMoviesPerCategory();
                $totalCategories = count($categoriesData); // Total number of categories

                // If details option is not provided, only show the total number of categories
                if (!$showDetails) {
                    $io->success(sprintf('Nombre total de catégories : %d', $totalCategories));
                } else {
                    // If details option is provided, show categories with the number of films
                    $io->success(sprintf('Nombre total de catégories : %d', $totalCategories));
                    $io->success('Nombre de films par catégorie :');
                    foreach ($categoriesData as $category) {
                        $io->text(sprintf('%s : %d films', $category['title'], $category['movieCount']));
                    }
                }

                break;

            default:
                $io->error('Merci d\'indiquer un type valide (movies, actors, categories).');
                return Command::FAILURE;
        }

        // Log personnalisé
        $this->logToFile($logFile, $type, $count ?? 0);

        return Command::SUCCESS;
    }

    private function countEntities(string $entityClass): int
    {
        return $this->entityManager->getRepository($entityClass)->count([]);
    }

    private function countMoviesPerCategory(): array
    {
        // Query to count movies per category
        $query = $this->entityManager->createQuery(
            'SELECT c.title, COUNT(m.id) AS movieCount
             FROM App\Entity\Category c
             LEFT JOIN c.movies m
             GROUP BY c.id'
        );

        return $query->getResult();
    }

    private function showMovieDetails(SymfonyStyle $io): void
    {
        $movies = $this->entityManager->getRepository('App\Entity\Movie')->findAll();

        $io->section('Movie Details:');
        foreach ($movies as $movie) {
            $io->text(sprintf(' - %s', $movie->getTitle()));
        }
    }

    private function showActorDetails(SymfonyStyle $io): void
    {
        $actors = $this->entityManager->getRepository('App\Entity\Actor')->findAll();

        $io->section('Actor Details:');
        foreach ($actors as $actor) {
            $io->text(sprintf(' - %s %s', $actor->getFirstname(), $actor->getLastname()));
        }
    }

    private function logToFile(string $filePath, string $type, int $count): void
    {
        $logMessage = sprintf("[%s] Type: %s, Count: %d\n", (new \DateTime())->format('Y-m-d H:i:s'), $type, $count);
        file_put_contents($filePath, $logMessage, FILE_APPEND);
    }
}

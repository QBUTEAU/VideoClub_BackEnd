<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\Slugify;

class DemoController extends AbstractController
{
    #[Route('/demo', name: 'app_demo')]
    public function index(): Response
    {

        $slug = new Slugify();
        $demo = $slug->slugify('Test du Slugify !');

        return $this->render(
            'demo/index.html.twig',
            [
            'controller_name' => 'DemoController',
            'datetime' => new \DateTime(),
            'slug' => $demo
            ]
        );
    }
}

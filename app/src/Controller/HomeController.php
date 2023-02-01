<?php

declare(strict_types=1);

namespace App\Controller;

use Runtime\Swoole\SymfonyRunner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller 2 or 3!',
            'hey' => '!!!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }

    #[Route('/restart', name: 'app_restart')]
    public function restart(): JsonResponse
    {
        SymfonyRunner::$sv->reload();

        return $this->json([
            'message' => 'Welcome to your new controller 2 or 3!',
            'hey' => 'ho lets goooo',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }
}


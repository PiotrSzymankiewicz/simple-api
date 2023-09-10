<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[Route('/add-message', name: 'add_message')]
    #[Route('/message/{uuid}', name: 'message')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
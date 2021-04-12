<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'Task Controller 2021',
        ]);
    }
}
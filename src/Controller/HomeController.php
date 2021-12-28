<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $noteRepository = $doctrine->getRepository(Note::class);
            $taskRepository = $doctrine->getRepository(Task::class);

            return $this->render('home.html.twig', [
                'notes' => $noteRepository->findAll(),
                'tasks' => $taskRepository->findAll(),
                ]
            );
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Note;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $customerRepository = $doctrine->getRepository(Customer::class);
            $taskRepository = $doctrine->getRepository(Task::class);

            return $this->render('home.html.twig', [
                'customers' => $customerRepository->findAll(),
                'tasks' => $taskRepository->findActive(),
                ]
            );
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}

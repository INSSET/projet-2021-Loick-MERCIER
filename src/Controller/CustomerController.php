<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\Type\CreateCustomerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customer/create', name: 'app_create_customer')]
    public function createCustomer(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customer = new Customer();
        $form = $this->createForm(CreateCustomerType::class, $customer);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Create new customer',
            'form' => $form,
        ]);
    }

    #[Route('/customer/{id}', name: 'app_customer', requirements: ['id' => '\d+'])]
    public function customer(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customerRepository = $doctrine->getRepository(Customer::class);

        return $this->render('customer/customerInformation.html.twig', [
            'customer' => $customerRepository->find($id),
        ]);
    }
}

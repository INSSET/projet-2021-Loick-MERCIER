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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setName(strtoupper($customer->getName()));
            $customer->setFirstName(ucfirst($customer->getFirstName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();
            return $this->redirectToRoute('app_customer', array('id' => $customer->getId()));
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Create new customer',
            'form' => $form,
        ]);
    }

    #[Route('/customer/{id}', name: 'app_customer', requirements: ['id' => '\d+'])]
    public function customer(ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customerRepository = $doctrine->getRepository(Customer::class);

        return $this->render('customer/customerInformation.html.twig', [
            'customer' => $customerRepository->find($id),
        ]);
    }

    #[Route('/customer/edit/{id}', name: 'app_edit_customer', requirements: ['id' => '\d+'])]
    public function updateCustomer(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customer = $doctrine->getRepository(Customer::class)->find($id);

        $form = $this->createForm(CreateCustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setName(strtoupper($customer->getName()));
            $customer->setFirstName(ucfirst($customer->getFirstName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_customer', array('id' => $id));
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Edit customer',
            'form' => $form,
        ]);
    }
}

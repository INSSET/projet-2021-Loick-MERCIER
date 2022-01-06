<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\Type\CompanyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/company/create', name: 'app_create_company')]
    public function createCustomer(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();
            //return $this->redirectToRoute($request->headers->get('referer'));
            return $this->redirectToRoute('app_create_customer');
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Create new company',
            'form' => $form,
        ]);
    }
}

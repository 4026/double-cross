<?php

namespace Four026\CabinetBundle\Controller;

use Four026\CabinetBundle\Entity\Document;
use Four026\CabinetBundle\Form\Type\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdministrationController extends Controller
{
    public function dashboardAction()
    {
        $user_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:WebUser');
        $document_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document');

        return $this->render('Four026CabinetBundle:Administration:dashboard.html.twig', [
            'user' => $this->getUser(),
            'users' => $user_repository->findAll(),
            'documents' => $document_repository->findAll()
        ]);
    }

    public function showCreateDocumentFormAction()
    {
        $form = $this->createForm(new DocumentType(), new Document(), [
            'action' => $this->generateUrl('submit_create_document_form'),
        ]);

        return $this->render('Four026CabinetBundle:Administration:createDocumentForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function submitCreateDocumentFormAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new DocumentType(), new Document());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('admin_dashboard'));
        }

        return $this->render('Four026CabinetBundle:Administration:createDocumentForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

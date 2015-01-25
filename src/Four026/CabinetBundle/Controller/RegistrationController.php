<?php

namespace Four026\CabinetBundle\Controller;

use Four026\CabinetBundle\Entity\WebUser;
use Four026\CabinetBundle\Form\Type\WebUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    public function showFormAction()
    {
        $form = $this->createForm(new WebUserType(), new WebUser(), [
            'action' => $this->generateUrl('submit_registration_form'),
        ]);

        return $this->render('Four026CabinetBundle:Registration:signupForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function submitFormAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new WebUserType(), new WebUser());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('four026_cabinet_login'));
        }

        return $this->render('Four026CabinetBundle:Registration:signupForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

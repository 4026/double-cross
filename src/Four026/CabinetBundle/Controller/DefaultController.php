<?php

namespace Four026\CabinetBundle\Controller;

use Four026\CabinetBundle\Entity\Document;
use Four026\CabinetBundle\Entity\WebUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('Four026CabinetBundle:Default:cover.html.twig');
    }

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'Four026CabinetBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }


    public function listDocumentsAction($character_name)
    {
        $document_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document');

        return $this->render('Four026CabinetBundle:Default:list_documents.html.twig',
            [
                'character_name' => $character_name,
                'documents' => $document_repository->findAll()
            ]
        );
    }

    public function readAction($character_name, $document_name)
    {
        return $this->render('Four026CabinetBundle:Default:read.html.twig', array('character_name' => $character_name, 'document_name', $document_name));
    }
}

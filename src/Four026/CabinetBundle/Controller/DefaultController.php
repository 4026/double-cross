<?php

namespace Four026\CabinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($character_name)
    {
        return $this->render('Four026CabinetBundle:Default:list_documents.html.twig', array('character_name' => $character_name));
    }

    public function readAction($character_name, $document_name)
    {
        return $this->render('Four026CabinetBundle:Default:read.html.twig', array('character_name' => $character_name, 'document_name', $document_name));
    }
}

<?php

namespace Four026\CabinetBundle\Controller;

use Four026\CabinetBundle\Entity\WebUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserDeskController extends Controller
{
    /**
     * Page that displays the user's current game state, which documents they've unlocked, which notes are available, etc.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deskMainAction()
    {
        return $this->render(
            'Four026CabinetBundle:UserDesk:deskMain.html.twig',
            [
                'user' => $this->getUser()
            ]
        );
    }

    /**
     * Page at which the current user can read the specified document.
     * @param int $document_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readDocumentAction($document_id)
    {
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();

        if (!in_array($document_id, $user->getUnlockedDocumentIds()))
        {
            throw $this->createAccessDeniedException('You have not unlocked this document yet.');
        }

        $document = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document')->find($document_id);
        return $this->render(
            'Four026CabinetBundle:UserDesk:read.html.twig',
            [
                'user'     => $user,
                'document' => $document
            ]
        );
    }

}

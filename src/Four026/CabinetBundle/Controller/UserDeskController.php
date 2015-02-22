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
        $generator = new \Four026\Passphrase\PassphraseGenerator(__DIR__ . '/../Resources/config/passphrase_wordlist.csv');

        $phrase = sprintf(
            "I'm told that the %s in %s is %s %s",
            $generator->getRandomWord('noun'),
            $generator->getRandomWord('place'),
            $generator->getRandomWord('adjective'),
            $generator->getRandomWord('during')
        );

        return $this->render(
            'Four026CabinetBundle:UserDesk:deskMain.html.twig',
            [
                'user' => $this->getUser(),
                'passphrase' => $phrase
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

    /**
     * Page at which the current user can read the specified note.
     * @param int $note_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function readNoteAction($note_id)
    {
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();

        if (!in_array($note_id, $user->getUnlockedNoteIds()))
        {
            throw $this->createAccessDeniedException('You have not unlocked this note yet.');
        }

        $note = $this->getDoctrine()->getRepository('Four026CabinetBundle:Note')->find($note_id);
        return $this->render(
            'Four026CabinetBundle:UserDesk:read.html.twig',
            [
                'user' => $user,
                'document' => $note
            ]
        );
    }
    
}

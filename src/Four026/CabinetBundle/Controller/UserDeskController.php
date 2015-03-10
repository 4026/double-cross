<?php

namespace Four026\CabinetBundle\Controller;

use Four026\CabinetBundle\Entity\CodePhraseHandshake;
use Four026\CabinetBundle\Entity\WebUser;
use Four026\CabinetBundle\Entity\WebUserRepository;
use Four026\CabinetBundle\Form\Type\CodePhraseHandshakeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDeskController extends Controller
{
    /**
     * Page that displays the user's current game state, which documents they've unlocked, which notes are available, etc.
     * @return Response
     */
    public function deskMainAction()
    {
        $fields = ['user' => $this->getUser()];

        if ($this->getUser()->getPartner() == null) {
            $fields['partnerForm'] = $this->createForm(
                new CodePhraseHandshakeType(),
                new CodePhraseHandshake(),
                ['action' => $this->generateUrl('submit_code_phrase_form')]
            )->createView();
        }

        return $this->render('Four026CabinetBundle:UserDesk:deskMain.html.twig', $fields);
    }

    /**
     * Page at which the current user can read the specified document.
     * @param int $document_id
     * @return Response
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
     * @return Response
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

    /**
     * Form submission handler for the passphrase handshake
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function submitCodePhraseHandshakeFormAction(Request $request)
    {
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();

        $handshake = new CodePhraseHandshake();
        $form = $this->createForm(new CodePhraseHandshakeType(), $handshake);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /**
             * @var WebUserRepository $repo
             */
            $repo = $this->getDoctrine()->getRepository('Four026CabinetBundle:WebUser');
            $prospective_partner = $repo->findOneByUsername($handshake->getPartnerName());

            //If the user exists, and the passphrases match...
            if (isset($prospective_partner) && $prospective_partner->getPassphrase() == $handshake->getCodePhrase()) {
                //...Assign the users as each others' partners.
                $user->setPartner($prospective_partner);
                $prospective_partner->setPartner($user);
                $em->flush();

                return $this->redirect($this->generateUrl('desk_main'));
            } else {
                $form->addError(new FormError("Incorrect code phrase for that agent."));
            }
        }

        $fields = ['user' => $user];
        $fields['partnerForm'] = $form->createView();

        return $this->render('Four026CabinetBundle:UserDesk:deskMain.html.twig', $fields);
    }
    
}

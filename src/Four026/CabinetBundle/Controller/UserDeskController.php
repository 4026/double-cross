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
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();
        $character_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:PlayerCharacter');
        $fields = [
            'user'       => $user,
            'characters' => $character_repository->findAll()
        ];

        if ($user->getPartner() == null) {
            //If the user doesn't have a partner yet, show them the form to register one...
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

        if (!in_array($document_id, $user->getUnlockedDocumentIds())) {
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

        if (!in_array($note_id, $user->getUnlockedNoteIds())) {
            throw $this->createAccessDeniedException('You have not unlocked this note yet.');
        }

        $note = $this->getDoctrine()->getRepository('Four026CabinetBundle:Note')->find($note_id);

        return $this->render(
            'Four026CabinetBundle:UserDesk:read.html.twig',
            [
                'user'     => $user,
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

    /**
     * Endpoint that handles a user selecting a character.
     * @param string $character_name
     * @return RedirectResponse
     */
    public function chooseCharacterAction($character_name)
    {
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();

        if ($user->getCharacter() != null) {
            return $this->redirect($this->generateUrl('desk_main'));
        }

        //Load character entities
        $character_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:PlayerCharacter');
        $selected_character = $character_repository->findOneByName($character_name);
        $other_character = $character_repository
            ->createQueryBuilder('c')
            ->where('c.name != :name')
            ->setParameter('name', $character_name)
            ->getQuery()
            ->getSingleResult();

        //Get starting documents to unlock for each player.
        $document_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document');
        $unlockType_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:DocumentUnlockMethod');
        $unlock_at_start = $unlockType_repository->findOneByName('Start');
        $user_document = $document_repository->findOneBy(
            [
                'character'  => $selected_character->getId(),
                'unlockType' => $unlock_at_start->getId()
            ]
        );
        $partner_document = $document_repository->findOneBy(
            [
                'character'  => $other_character->getId(),
                'unlockType' => $unlock_at_start->getId()
            ]
        );

        //Set characters and unlocked documents for the user and their partner.
        $user
            ->setCharacter($selected_character)
            ->addUnlockedDocument($user_document);

        $user->getPartner()
            ->setCharacter($other_character)
            ->addUnlockedDocument($partner_document);

        //Save changes
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('desk_main'));
    }

}

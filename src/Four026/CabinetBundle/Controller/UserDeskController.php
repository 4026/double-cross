<?php

namespace Four026\CabinetBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Four026\CabinetBundle\Entity\CodePhraseHandshake;
use Four026\CabinetBundle\Entity\Document;
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
     * Page that displays the user's current game state, which documents they've unlocked, which notes are available,
     * etc.
     *
     * @return Response
     */
    public function deskMainAction()
    {
        /**
         * @var WebUser $user
         */
        $user                 = $this->getUser();
        $character_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:PlayerCharacter');
        $fields               = [
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
     *
     * @param int $document_id
     *
     * @return Response
     */
    public function readDocumentAction($document_id)
    {
        //Load entities from DB
        /**
         * @var WebUser $user
         */
        $user                  = $this->getUser();
        $unlocked_document_ids = $user->getUnlockedDocumentIds();

        /**
         * @var Document $document
         */
        $document = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document')->find($document_id);

        //Check user should be able to view this document
        if (!in_array($document_id, $unlocked_document_ids)) {
            throw $this->createAccessDeniedException('You have not unlocked this document yet.');
        }

        //Compile some information on how to unlock the next document.
        $unlockable_document_ids    = $document->getNextDocumentIds();
        $unlockable_documents       = $document->getNextDocuments();
        $has_unlocked_next_document = count(array_intersect($unlocked_document_ids, $unlockable_document_ids)) != 0;
        $unlock_type                = $unlockable_documents[0]->getUnlockType()->getName();
        $unlock_prompt              = $unlockable_documents[0]->getUnlockPrompt();

        return $this->render(
            'Four026CabinetBundle:UserDesk:read.html.twig',
            [
                'user'                    => $user,
                'document'                => $document,
                'hasUnlockedNextDocument' => $has_unlocked_next_document,
                'unlockType'              => $unlock_type,
                'unlockPrompt'            => $unlock_prompt
            ]
        );
    }

    /**
     * Page at which the current user can read the specified note.
     *
     * @param int $note_id
     *
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
     *
     * @return RedirectResponse|Response
     */
    public function submitCodePhraseHandshakeFormAction(Request $request)
    {
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();

        $handshake = new CodePhraseHandshake();
        $form      = $this->createForm(new CodePhraseHandshakeType(), $handshake);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /**
             * @var WebUserRepository $repo
             */
            $repo                = $this->getDoctrine()->getRepository('Four026CabinetBundle:WebUser');
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

        $fields                = ['user' => $user];
        $fields['partnerForm'] = $form->createView();

        return $this->render('Four026CabinetBundle:UserDesk:deskMain.html.twig', $fields);
    }

    /**
     * Endpoint that handles a user selecting a character.
     *
     * @param string $character_name
     *
     * @return RedirectResponse
     */
    public function chooseCharacterAction($character_name)
    {
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();

        if ($user->getCharacter() != null) {
            $this->addFlash(
                'alert-error',
                'Your character has already been assigned. You are ' . $user->getCharacter()->getName() . '.'
            );

            return $this->redirect($this->generateUrl('desk_main'));
        }

        //Load character entities
        /**
         * @todo Create a character repository that defines its methods and the types it returns better than EntityRepository.
         * @var EntityRepository $character_repository
         */
        $character_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:PlayerCharacter');
        $selected_character   = $character_repository->findOneByName($character_name);
        $other_character      = $character_repository
            ->createQueryBuilder('c')
            ->where('c.name != :name')
            ->setParameter('name', $character_name)
            ->getQuery()
            ->getSingleResult();

        //Get starting documents to unlock for each player.
        $document_repository   = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document');
        $unlockType_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:DocumentUnlockMethod');
        $unlock_at_start       = $unlockType_repository->findOneByName('Start');
        $user_document         = $document_repository->findOneBy(
            [
                'character'  => $selected_character->getId(),
                'unlockType' => $unlock_at_start->getId()
            ]
        );
        $partner_document      = $document_repository->findOneBy(
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

    public function DocumentTryPasswordAction(Request $request, $document_id)
    {
        //Load entities
        /**
         * @var WebUser $user
         */
        $user = $this->getUser();
        $partner = $user->getPartner();

        /**
         * @todo Create a Document repository that defines its methods and the types it returns better than EntityRepository.
         * @var EntityRepository $document_repository
         */
        $document_repository = $this->getDoctrine()->getRepository('Four026CabinetBundle:Document');
        /**
         * @var Document $document
         */
        $document = $document_repository->find($document_id);

        //Check user should be able to unlock this document (ie. that they already have the prior document)
        if (!$user->getUnlockedDocuments()->contains($document->getPreviousDocument())) {
            $this->addFlash('alert-error', 'You have not unlocked the previous document yet.');

            return $this->redirect(
                $this->generateUrl('desk_main')
            );
        }

        //Check user has not already unlocked this document
        if ($user->getUnlockedDocuments()->contains($document)) {
            $this->addFlash('alert-error', 'You have already unlocked that document.');

            return $this->redirect(
                $this->generateUrl('desk_main')
            );
        }

        //Check password was provided
        if (!$request->request->has('password')) {
            $this->addFlash('alert-error', 'No password provided to unlock document.');

            return $this->redirect(
                $this->generateUrl('read_document', ['document_id' => $document->getPreviousDocument()->getId()])
            );
        }

        //Check password is correct
        if ($request->request->get('password') != $document->getUnlockParam()) {
            $this->addFlash('alert-warning', 'Incorrect password.');

            return $this->redirect(
                $this->generateUrl('read_document', ['document_id' => $document->getPreviousDocument()->getId()])
            );
        }

        //Unlock document for current user.
        $user->addUnlockedDocument($document);

        /**
         * Find documents for the partner's character that have the "Partner" unlock method.
         * @var Document[] $partner_documents
         */
        $partner_documents = $document_repository->createQueryBuilder('d')
            ->join('d.unlockType', 't')
            ->join('d.character', 'c')
            ->where('t.name = :type')
            ->andWhere('c.id = :character')
            ->setParameter('type', 'Partner')
            ->setParameter('character', $partner->getCharacter()->getId())
            ->getQuery()
            ->getResult();

        foreach ($partner_documents as $partner_document) {
            // If the partner has unlocked the previous document to a document that has the "Partner" unlock method,
            // unlock that document now.
            if (in_array($partner_document->getPreviousDocument()->getId(), $partner->getUnlockedDocumentIds())) {
                $partner->addUnlockedDocument($partner_document);
            }
        }

        //Save changes
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('alert-success', 'New document unlocked!');
        return $this->redirect( $this->generateUrl('desk_main'));
    }

}

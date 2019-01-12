<?php

namespace SoftUniBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SoftUniBlogBundle\Entity\Message;
use SoftUniBlogBundle\Entity\User;
use SoftUniBlogBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends Controller
{
    /**
     * @Route("/user/{id}/message/{articleId}", name="user_message")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param int $articleId
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addMessageAction(Request $request, int $articleId, int $id)
    {
//        $str = $_SERVER['HTTP_REFERER'];
//        $articleId = substr($str, strrpos($str, '/') + 1);

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $sender = $this->getUser();
            $recipient = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id);

            $message
                ->setSender($sender)
                ->setRecipient($recipient);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('message', 'Message sent');

            return $this->redirectToRoute('article_view',
                ['id' => $articleId]);
        }

        return $this->render('user/sendMessages.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("user/mailbox", name="user_mailbox")
     */
    public function mailBoxAction()
    {
        $currentUser = $this->getUser();
//        $receivedMessages = $currentUser
//            ->getRecipientMessages();
        $receivedMessages = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findBy(['recipient' => $currentUser]);

        return $this->render('user/mailbox.html.twig',
            ['messages' => $receivedMessages]);
    }

    /**
     * @Route("user/mailbox/message/{id}", name="user_current_message")
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageAction(Request $request, int $id)
    {
        $receivedMsg = $this->getDoctrine()
            ->getRepository(Message::class)
            ->find($id);
        $receivedMsg->setIsReaded(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($receivedMsg);
        $em->flush();

        $answer = new Message();
        $form = $this->createForm(MessageType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $sender = $this->getUser();
            $recipient = $receivedMsg->getSender();

            $answer->setSender($sender)
                ->setRecipient($recipient);

            $em->persist($answer);
            $em->flush();

            $this->addFlash('message', 'Message sent successfully!');

            return $this->redirectToRoute('user_current_message',
                ['id' => $id]);
        }

        return $this->render('user/message.html.twig',
            ['msg' => $receivedMsg, 'form' => $form->createView()]);
    }
}

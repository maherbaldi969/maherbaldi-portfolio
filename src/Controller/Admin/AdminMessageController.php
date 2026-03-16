<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/messages')]
#[IsGranted('ROLE_ADMIN')]
class AdminMessageController extends AbstractController
{
    #[Route('', name: 'app_admin_messages')]
    public function index(ContactMessageRepository $repository): Response
    {
        return $this->render('admin/messages/index.html.twig', [
            'messages' => $repository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_messages_show', requirements: ['id' => '\d+'])]
    public function show(ContactMessage $message, EntityManagerInterface $em): Response
    {
        if (!$message->isRead()) {
            $message->setIsRead(true);
            $em->flush();
        }

        return $this->render('admin/messages/show.html.twig', [
            'messageItem' => $message,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_messages_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(ContactMessage $message, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_message_'.$message->getId(), $request->request->get('_token'))) {
            $em->remove($message);
            $em->flush();
            $this->addFlash('success', 'Message supprimé.');
        }

        return $this->redirectToRoute('app_admin_messages');
    }
}
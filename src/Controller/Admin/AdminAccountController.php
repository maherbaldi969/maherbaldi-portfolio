<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/account')]
#[IsGranted('ROLE_ADMIN')]
class AdminAccountController extends AbstractController
{
    #[Route('', name: 'app_admin_account')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non valide.');
        }

        $form = $this->createForm(AdminAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $currentPassword = $form->get('currentPassword')->getData();
                $newPassword = $form->get('newPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();

                if ($newPassword || $confirmPassword || $currentPassword) {
                    if (!$currentPassword) {
                        $this->addFlash('error', 'Veuillez saisir votre mot de passe actuel.');
                        return $this->redirectToRoute('app_admin_account');
                    }

                    if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                        $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                        return $this->redirectToRoute('app_admin_account');
                    }

                    if (!$newPassword) {
                        $this->addFlash('error', 'Veuillez saisir le nouveau mot de passe.');
                        return $this->redirectToRoute('app_admin_account');
                    }

                    if ($newPassword !== $confirmPassword) {
                        $this->addFlash('error', 'La confirmation du mot de passe ne correspond pas.');
                        return $this->redirectToRoute('app_admin_account');
                    }

                    if (mb_strlen($newPassword) < 6) {
                        $this->addFlash('error', 'Le nouveau mot de passe doit contenir au moins 6 caractères.');
                        return $this->redirectToRoute('app_admin_account');
                    }

                    $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                    $user->setPassword($hashedPassword);
                }

                $em->flush();

                $this->addFlash('success', 'Compte mis à jour avec succès.');
                return $this->redirectToRoute('app_admin_account');
            } else {
                $this->addFlash('error', 'Le formulaire contient des erreurs.');
            }
        }

        return $this->render('admin/account/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
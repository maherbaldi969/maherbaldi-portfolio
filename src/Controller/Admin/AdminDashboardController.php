<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();

        $adminName = 'Admin';
        $adminEmail = '';

        if ($user instanceof User) {
            $adminName = $user->getFullName() ?? 'Admin';
            $adminEmail = $user->getEmail() ?? '';
        }

        return $this->render('admin/dashboard.html.twig', [
            'adminName' => $adminName,
            'adminEmail' => $adminEmail,
        ]);
    }
}
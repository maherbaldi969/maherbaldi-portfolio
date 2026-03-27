<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use App\Repository\ExperienceRepository;
use App\Repository\ContactMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function index(
        ProjectRepository $projectRepository,
        SkillRepository $skillRepository,
        ExperienceRepository $experienceRepository,
        ContactMessageRepository $contactMessageRepository
    ): Response
    {
        $user = $this->getUser();

        $adminName = 'Admin';
        $adminEmail = '';

        if ($user instanceof User) {
            $adminName = $user->getFullName() ?? 'Admin';
            $adminEmail = $user->getEmail() ?? '';
        }

        // Compter les éléments dynamiques
        $projectsCount = $projectRepository->count([]);
        $skillsCount = $skillRepository->count([]);
        $experiencesCount = $experienceRepository->count([]);
        $messagesCount = $contactMessageRepository->count(['isRead' => false]);

        return $this->render('admin/dashboard.html.twig', [
            'adminName' => $adminName,
            'adminEmail' => $adminEmail,
            'projectsCount' => $projectsCount,
            'skillsCount' => $skillsCount,
            'experiencesCount' => $experiencesCount,
            'messagesCount' => $messagesCount,
        ]);
    }
}

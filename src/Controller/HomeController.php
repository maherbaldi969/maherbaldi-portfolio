<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use App\Repository\EducationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\ProjectRepository;
use App\Repository\SiteSettingRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        ProjectRepository $projectRepository,
        SkillRepository $skillRepository,
        ExperienceRepository $experienceRepository,
        EducationRepository $educationRepository,
        SiteSettingRepository $siteSettingRepository
    ): Response {
        $projects = $projectRepository->findPublishedProjects();

        $frontendSkills = $skillRepository->findPublishedByCategory('frontend');
        $backendSkills = $skillRepository->findPublishedByCategory('backend');
        $databaseToolsSkills = $skillRepository->findPublishedByCategory('database_tools');
        $allTechnologyTags = $skillRepository->findPublishedTags();

        $experiences = $experienceRepository->findPublishedOrdered();
        $educations = $educationRepository->findPublishedOrdered();

        $siteSettings = $siteSettingRepository->getMainSettings();

        // Calcul des statistiques dynamiques pour la section About
        $yearsExperience = date('Y') - 2022;
        $projectsCount = $projectRepository->count([]);
        $coreFrameworksCount = $skillRepository->count(['category' => 'framework']);

        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contactMessage);
            $em->flush();

            $this->addFlash('success', 'Your message has been sent successfully.');
            return $this->redirectToRoute('app_home', ['_fragment' => 'contact']);
        }

        return $this->render('home/index.html.twig', [
            'projects_db' => $projects,
            'frontendSkills' => $frontendSkills,
            'backendSkills' => $backendSkills,
            'databaseToolsSkills' => $databaseToolsSkills,
            'allTechnologyTags' => $allTechnologyTags,
            'experiences' => $experiences,
            'educations' => $educations,
            'siteSettings' => $siteSettings,
            'contactForm' => $form->createView(),
            'yearsExperience' => $yearsExperience,
            'projectsCount' => $projectsCount,
            'coreFrameworksCount' => $coreFrameworksCount,
        ]);
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/experience')]
#[IsGranted('ROLE_ADMIN')]
class AdminExperienceController extends AbstractController
{
    #[Route('', name: 'app_admin_experience')]
    public function index(ExperienceRepository $repository): Response
    {
        return $this->render('admin/experience/index.html.twig', [
            'experiences' => $repository->findBy([], ['displayOrder' => 'ASC', 'id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_admin_experience_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setAchievements($this->explodeLines($form->get('achievementsText')->getData()));
            $experience->setTechnologies($this->explodeLines($form->get('technologiesText')->getData()));
            $experience->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($experience);
            $em->flush();

            $this->addFlash('success', 'Experience ajoutée avec succès.');
            return $this->redirectToRoute('app_admin_experience');
        }

        return $this->render('admin/experience/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Add Experience',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_experience_edit', requirements: ['id' => '\d+'])]
    public function edit(Experience $experience, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->get('achievementsText')->setData(implode("\n", $experience->getAchievements()));
        $form->get('technologiesText')->setData(implode("\n", $experience->getTechnologies()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setAchievements($this->explodeLines($form->get('achievementsText')->getData()));
            $experience->setTechnologies($this->explodeLines($form->get('technologiesText')->getData()));
            $experience->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();

            $this->addFlash('success', 'Experience modifiée avec succès.');
            return $this->redirectToRoute('app_admin_experience');
        }

        return $this->render('admin/experience/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Edit Experience',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_experience_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Experience $experience, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_experience_'.$experience->getId(), $request->request->get('_token'))) {
            $em->remove($experience);
            $em->flush();
            $this->addFlash('success', 'Experience supprimée.');
        }

        return $this->redirectToRoute('app_admin_experience');
    }

    #[Route('/{id}/toggle-publish', name: 'app_admin_experience_toggle_publish', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function togglePublish(Experience $experience, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle_experience_'.$experience->getId(), $request->request->get('_token'))) {
            $experience->setIsPublished(!$experience->isPublished());
            $experience->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', $experience->isPublished() ? 'Experience publiée.' : 'Experience dépubliée.');
        }

        return $this->redirectToRoute('app_admin_experience');
    }

    private function explodeLines(?string $value): array
    {
        if (!$value) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $value);
        $lines = array_map(fn ($item) => trim((string) $item), $lines);

        return array_values(array_filter($lines, fn ($item) => $item !== ''));
    }
}
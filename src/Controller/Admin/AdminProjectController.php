<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/projects')]
#[IsGranted('ROLE_ADMIN')]
class AdminProjectController extends AbstractController
{
    #[Route('', name: 'app_admin_projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('projects/index.html.twig', [
            'projects' => $projectRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_admin_projects_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleProjectImageUpload($form, $project, $slugger);

            $project->setTechnologies($this->explodeLines($form->get('technologiesText')->getData()));
            $project->setFeatures($this->explodeLines($form->get('featuresText')->getData()));
            $project->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'Projet ajouté avec succès.');
            return $this->redirectToRoute('app_admin_projects');
        }

        return $this->render('projects/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Add Project',
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_projects_edit', requirements: ['id' => '\d+'])]
    public function edit(
        Project $project,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $form = $this->createForm(ProjectType::class, $project);
        $form->get('technologiesText')->setData(implode("\n", $project->getTechnologies()));
        $form->get('featuresText')->setData(implode("\n", $project->getFeatures()));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleProjectImageUpload($form, $project, $slugger);

            $project->setTechnologies($this->explodeLines($form->get('technologiesText')->getData()));
            $project->setFeatures($this->explodeLines($form->get('featuresText')->getData()));
            $project->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();

            $this->addFlash('success', 'Projet modifié avec succès.');
            return $this->redirectToRoute('app_admin_projects');
        }

        return $this->render('projects/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Edit Project',
            'project' => $project,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_projects_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_project_'.$project->getId(), $request->request->get('_token'))) {
            $em->remove($project);
            $em->flush();
            $this->addFlash('success', 'Projet supprimé.');
        }

        return $this->redirectToRoute('app_admin_projects');
    }

    #[Route('/{id}/toggle-publish', name: 'app_admin_projects_toggle_publish', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function togglePublish(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle_publish_'.$project->getId(), $request->request->get('_token'))) {
            $project->setIsPublished(!$project->isPublished());
            $project->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', $project->isPublished() ? 'Projet publié.' : 'Projet dépublié.');
        }

        return $this->redirectToRoute('app_admin_projects');
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

    private function handleProjectImageUpload($form, Project $project, SluggerInterface $slugger): void
    {
        $imageFile = $form->get('imageFile')->getData();

        if (!$imageFile) {
            return;
        }

        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        try {
            $imageFile->move(
                $this->getParameter('kernel.project_dir').'/public/uploads/projects',
                $newFilename
            );

            // chemin enregistré en base
            $project->setImagePath('uploads/projects/'.$newFilename);
        } catch (FileException $e) {
            throw new \RuntimeException('Erreur lors de l’upload de l’image.');
        }
    }
}
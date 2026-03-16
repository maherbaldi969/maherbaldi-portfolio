<?php

namespace App\Controller\Admin;

use App\Entity\Education;
use App\Form\EducationType;
use App\Repository\EducationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/education')]
#[IsGranted('ROLE_ADMIN')]
class AdminEducationController extends AbstractController
{
    #[Route('', name: 'app_admin_education')]
    public function index(EducationRepository $repository): Response
    {
        return $this->render('admin/education/index.html.twig', [
            'educations' => $repository->findBy([], ['displayOrder' => 'ASC', 'id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_admin_education_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $education = new Education();
        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $education->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($education);
            $em->flush();

            $this->addFlash('success', 'Education ajoutée avec succès.');
            return $this->redirectToRoute('app_admin_education');
        }

        return $this->render('admin/education/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Add Education',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_education_edit', requirements: ['id' => '\d+'])]
    public function edit(Education $education, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $education->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Education modifiée avec succès.');
            return $this->redirectToRoute('app_admin_education');
        }

        return $this->render('admin/education/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Edit Education',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_education_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Education $education, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_education_'.$education->getId(), $request->request->get('_token'))) {
            $em->remove($education);
            $em->flush();
            $this->addFlash('success', 'Education supprimée.');
        }

        return $this->redirectToRoute('app_admin_education');
    }

    #[Route('/{id}/toggle-publish', name: 'app_admin_education_toggle_publish', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function togglePublish(Education $education, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle_education_'.$education->getId(), $request->request->get('_token'))) {
            $education->setIsPublished(!$education->isPublished());
            $education->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', $education->isPublished() ? 'Education publiée.' : 'Education dépubliée.');
        }

        return $this->redirectToRoute('app_admin_education');
    }
}
<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/skills')]
#[IsGranted('ROLE_ADMIN')]
class AdminSkillController extends AbstractController
{
    #[Route('', name: 'app_admin_skills')]
    public function index(SkillRepository $repository): Response
    {
        return $this->render('admin/skills/index.html.twig', [
            'skills' => $repository->findBy([], ['displayOrder' => 'ASC', 'id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_admin_skills_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($skill);
            $em->flush();

            $this->addFlash('success', 'Compétence ajoutée avec succès.');
            return $this->redirectToRoute('app_admin_skills');
        }

        return $this->render('admin/skills/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Add Skill',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_skills_edit', requirements: ['id' => '\d+'])]
    public function edit(Skill $skill, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skill->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Compétence modifiée avec succès.');
            return $this->redirectToRoute('app_admin_skills');
        }

        return $this->render('admin/skills/form.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'Edit Skill',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_skills_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Skill $skill, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_skill_'.$skill->getId(), $request->request->get('_token'))) {
            $em->remove($skill);
            $em->flush();
            $this->addFlash('success', 'Compétence supprimée.');
        }

        return $this->redirectToRoute('app_admin_skills');
    }

    #[Route('/{id}/toggle-publish', name: 'app_admin_skills_toggle_publish', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function togglePublish(Skill $skill, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle_skill_'.$skill->getId(), $request->request->get('_token'))) {
            $skill->setIsPublished(!$skill->isPublished());
            $skill->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', $skill->isPublished() ? 'Compétence publiée.' : 'Compétence dépubliée.');
        }

        return $this->redirectToRoute('app_admin_skills');
    }
}
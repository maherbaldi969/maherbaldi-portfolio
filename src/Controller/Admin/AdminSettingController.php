<?php

namespace App\Controller\Admin;

use App\Entity\SiteSetting;
use App\Form\SiteSettingType;
use App\Repository\SiteSettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/settings')]
#[IsGranted('ROLE_ADMIN')]
class AdminSettingController extends AbstractController
{
    #[Route('', name: 'app_admin_settings')]
    public function index(
        Request $request,
        SiteSettingRepository $repository,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $settings = $repository->getMainSettings();

        if (!$settings) {
            $settings = new SiteSetting();
            $em->persist($settings);
            $em->flush();
        }

        $form = $this->createForm(SiteSettingType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cvFile = $form->get('cvFile')->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.pdf';

                try {
                    $cvFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/cv',
                        $newFilename
                    );

                    $settings->setCvFilePath('uploads/cv/' . $newFilename);
                } catch (FileException $e) {
                    throw new \RuntimeException('Erreur lors de l’upload du CV.');
                }
            }

            $settings->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Settings mis à jour avec succès.');
            return $this->redirectToRoute('app_admin_settings');
        }

        return $this->render('admin/settings/index.html.twig', [
            'form' => $form->createView(),
            'settings' => $settings,
        ]);
    }

    #[Route('/delete-cv', name: 'app_admin_settings_delete_cv', methods: ['POST'])]
    public function deleteCv(
        Request $request,
        SiteSettingRepository $repository,
        EntityManagerInterface $em
    ): Response {
        $settings = $repository->getMainSettings();

        if (!$settings) {
            return $this->redirectToRoute('app_admin_settings');
        }

        if ($this->isCsrfTokenValid('delete_cv', $request->request->get('_token'))) {
            $currentCv = $settings->getCvFilePath();

            if ($currentCv) {
                $fullPath = $this->getParameter('kernel.project_dir') . '/public/' . $currentCv;

                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }

                $settings->setCvFilePath(null);
                $settings->setUpdatedAt(new \DateTimeImmutable());
                $em->flush();
            }

            $this->addFlash('success', 'CV supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_settings');
    }
}
<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnnouncementController extends AbstractController
{
    #[Route(path: '/home/announcement', name: 'app_announcement')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $announcements = $entityManager->getRepository(Announcement::class)->findAll();

        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcements
        ]);
    }

    #[Route(path: '/home/announcement/add', name: 'app_announcement_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response | RedirectResponse
    {
        $announcement = new Announcement();
        
        $form = $this->createForm(AnnouncementType::class, $announcement);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            /**@var Announcement announcement */
            $announcement = $form->getData();
            $announcement->setAuthorIdentifier($this->getUser()->getUserIdentifier());
            
            $entityManager->persist($announcement);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_announcement');
        }
        
        return $this->render('announcement/add.html.twig', [
            'form' => $form
        ]);
    }
    
    #[Route(path: '/home/announcement/remove/{id}', name: 'app_announcement_remove')]
    public function remove(EntityManagerInterface $entityManager, Announcement $announcement): RedirectResponse
    {
        $entityManager->remove($announcement);
        $entityManager->flush();

        return $this->redirectToRoute("app_announcement");
    }

    #[Route(path: '/home/announcement/{id}', name: 'app_announcement_view')]
    public function view(Announcement $announcement): Response | RedirectResponse
    {
        return $this->render('announcement/view.html.twig', [
            "announcement" => $announcement
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Announcement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnnouncementController extends AbstractController
{
    #[Route('/announcement', name: 'app_announcement')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $announcements = $entityManager->getRepository(Announcement::class)->findAll();

        return $this->render('announcement/index.html.twig', [
            'announcements' => $announcements
        ]);
    }
}

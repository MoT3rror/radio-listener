<?php

namespace App\Controller;

use App\Entity\Radio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {}
      
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $radios = $this->entityManager->getRepository(Radio::class)->findAll();

        return $this->render('home/index.html.twig', [
            'radios' => $radios,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    /**
     * @Route("/song", name="song")
     */
    public function index()
    {
        return $this->render('song/index.html.twig', [
            'controller_name' => 'SongController',
        ]);
    }
}

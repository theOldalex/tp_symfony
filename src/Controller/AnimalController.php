<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnimalController extends AbstractController {
    /**
     * @Route("/animaux", name="animaux")
     */
    public function kamoulox(AnimalRepository $repository): Response {
        $animaux = $repository->findAll();

        // dd($animaux);

        return $this->render('animal/index.html.twig', [
            'pets' => $animaux
        ]);
    }

    /**
     * @Route("/animaux/{id}/delete", name="delete_animal")
     */
    public function delete($id, AnimalRepository $repo, EntityManagerInterface $em) {
        $animal = $repo->find($id);
        if (empty($animal)) throw new NotFoundHttpException;
        
        $em->remove($animal);
        $em->flush();

        return $this->redirectToRoute('animaux');
    }
}

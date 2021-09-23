<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ProductController extends AbstractController {
    /**
     * @Route("/products/create", name="create_product")
     */
    public function create(Request $request, EntityManagerInterface $entityManager) {

        $produit = new Product;

        /**
         * On utilise le FormBuilder directement
         */
        $formulaire = $this->createFormBuilder($produit)
            ->add('label', TextType::class)
            ->add('prix', MoneyType::class, [
                'invalid_message' => 'Rentre un prix, stp'
            ])
            ->add('photoPrincipale', UrlType::class)
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->getForm();

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('products');
        } else {
            return $this->render('product/form.html.twig', [
                'type' => 'Ajouter',
                'formView' => $formulaire->createView()
            ]);
        }
    }

    /**
     * @Route("/products/{id}/update", name="update_product")
     */
    public function update(Product $produit, Request $request, EntityManagerInterface $entityManager) {
        // $repository = $this->getDoctrine()->getRepository(Product::class);
        // $produit = $repository->find($id);

        /**
         * On utilise une classe annexe qui construit le formulaire
         */
        $formulaire = $this->createForm(ProductType::class, $produit);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('products');
        } else {
            return $this->render('product/form.html.twig', [
                'type' => 'Modifier',
                'formView' => $formulaire->createView()
            ]);
        }
    }

    /**
     * @Route("/products", name="products")
     */
    public function index(): Response {
        /**
         * Le repository nous sert à RECUPERER des entités
         * 
         * On le récupère à la main ici
         */
        $repository = $this
            ->getDoctrine()
            ->getRepository(Product::class);

        $produits = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/products/{id}", name="one_product")
     */
    public function one($id, ProductRepository $repository) {
        /**
         * Le repository nous sert à RECUPERER des entités
         * 
         * On le récupère grâce au mécanisme 
         * qui s'appelle "injection de dépendance"
         * (grâce à Symfony)
         * qui me permet de l'avoir en paramètre
         */
        $produit = $repository->find($id);

        return $this->render('product/one.html.twig', [
            'produit' => $produit
        ]);
    }

    /**
     * @Route("/products/{id}/delete", name="delete_product")
     */
    public function delete(Product $produit, EntityManagerInterface $entityManager) {
        /**
         * On demande à Symfony de nous 
         * récupérer automatiquement un produit
         * 
         * Il va tenter de la faire grâce à l'injection de dépendance
         * 
         * Pour trouver le bon produit, 
         * il va prendre le premier paramètre d'URL
         * et le considérer comme la clef primaire 
         * qui permet de retrouver le produit en question
         */

        /**
         * L'entity Manager nous sert 
         * à MODIFIER, CREER, SUPPRIMER des entités
         * 
         * On l'injecte grâce à l'injection de dépendance
         */

        // dd($produit); // dump & die

        $entityManager->remove($produit); // On ajoute le delete à la TODO-list
        $entityManager->flush(); // On exécute la TODO-list

        return $this->redirectToRoute('products');
    }
}

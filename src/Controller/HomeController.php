<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(ProductRepository $productRepository)
    {

        $product = $productRepository->findBy([], [], 3);


        return $this->render(
            'home.html.twig',
            [
                'product' => $product
            ]
        );
    }
}

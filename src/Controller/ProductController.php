<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Monolog\Handler\Handler;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\FloatType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Validator;
use Doctrine\Common\Annotations\Annotation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category", priority=-1)
     */
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        //remonte une category grace au slug
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw new NotFoundHttpException("Aucune catégorie de ce nom");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show",priority=-1)
     */
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);
        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("admin/product/{id}/edit" , name="product_edit")
     */
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        // $product = new product;
        // $resultat = $validator->validate($product);
        // if ($resultat->count() > 0) {
        //     dd("il y a des erreurs", $resultat);
        // }

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();


            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render(
            'product/edit.html.twig',
            [
                'product' => $product,
                'formView' => $formView
            ]
        );
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */

    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {


        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        //prise en compte de la requette
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //nom slug = nom du product 
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();
            //redirection
            return $this->redirectToRoute(
                'product_show',
                [
                    'category_slug' => $product->getCategory()->getSlug(),
                    'slug' => $product->getSlug()
                ]
            );
        }
        $formView = $form->createView();
        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}

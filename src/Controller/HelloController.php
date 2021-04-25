<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Detector;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloController
{

    /**Au sein des contorlleurs et uniquement au sein des controllers
     * on peut se faire livrer dans
     *  une méthode couplé a un route le service
     */
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route ("/hello/{nom?world}", name="helloName")
     *
     */
    function helloName(
        $nom = "World",
        LoggerInterface $logger,
        Calculator $calculator,
        Slugify $slugify,
        Environment $twig,
        Detector $detector
    ) {
        $html = $twig->render('hello.html.twig', [
            'nom' => $nom,


        ]);
        // dump($detector->detect(101));
        // dump($detector->detect(10));

        // dump($twig);

        // dump($slugify->slugify("Hello Wolrd"));

        // $logger->error("Mon message de log!!!");

        // $tva = $calculator->calcul(100);

        // dump($tva);
        return new Response($html);
    }
}

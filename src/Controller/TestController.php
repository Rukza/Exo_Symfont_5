<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    protected $calculator;
    /**autowiring pour avoir une instance de la classe
     *  calculator (tout dans src peux etre auto wire) */
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }
    /**
     * @Route("/", name="index")
     *
     */
    function index()
    {
        $tva = $this->calculator->calcul(100);
        dump($tva);
        dd("cela fonctionne");
    }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET", "POST"}, host="127.0.0.1", schemes={"https", "http"})
     *
     */
    function test(Request $request, $age)
    {
        //dump($request);
        //$age = $request->attributes->get('age');

        return new Response("Vous avez $age ans");
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return new RedirectResponse('https://www.dentalservices.net');

    }
}

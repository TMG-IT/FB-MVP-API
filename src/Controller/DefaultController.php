<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function homepageAction(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}

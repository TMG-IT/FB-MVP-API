<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class CorsController
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Handle OPTIONS requests. Check router for allowed methods and return them.
     *
     * @Route("{path}", requirements={"path": ".*"}, methods={"OPTIONS"}, name="cors_options")
     *
     * @param string $path
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function corsOptionsAction(string $path): Response
    {
        $path = '/'.$path;

        $methods = [
            'GET',
            'POST',
            'PUT',
            'PATCH',
            'DELETE',
        ];

        $allowedMethods = [
            'OPTIONS',
        ];

        $routerContext = $this->router->getContext();

        foreach ($methods as $method) {
            $routerContext->setMethod($method);

            try {
                // Throws exception if not found
                $this->router->match($path);

                // method allowed, add it to the list
                $allowedMethods[] = $method;
            } catch (\RuntimeException $exception) {
                // route/method combination now allowed, skip it
            }
        }

        $allowedMethodsString = implode(', ', $allowedMethods);

        return new Response('', Response::HTTP_OK, [
            'Allow' => $allowedMethodsString,
            'Access-Control-Allow-Methods' => $allowedMethodsString,
        ]);
    }
}

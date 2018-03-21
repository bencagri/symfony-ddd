<?php

namespace App\Authorization\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Swagger\Annotations as SWG;

class SampleController
{

    /**
     * @Route("/api/test/annotation")
     * @Method("GET")
     * @SWG\Response(
     *   response="200",
     *   description="Returned when successful"
     * )
     * @SWG\Tag(name="sample")
     */
    public function index()
    {
        return new JsonResponse(['test annotation']);
    }
}
<?php


namespace App\Aurora\Http\Controller;


use App\Aurora\App\Support\FractalService;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppController extends Controller
{

    /**
     * @var FractalService
     */
    private $fractalService;

    public function __construct(FractalService $fractalService)
    {
        $this->fractalService = $fractalService;
    }

    /**
     * @param $resource
     * @param bool $success
     * @param int $code
     * @return JsonResponse
     */
    protected function response($resource, $success = true, $code = Response::HTTP_OK)
    {
        if ($resource instanceof ResourceInterface){

            $this->fractalService->setSerializer(new JsonApiSerializer());

            //if there is an include, set transformer include
            if ( isset($_GET['include']) && !empty($_GET['include'])) {
                $this->fractalService->parseIncludes($_GET['include']);
            }

            $resource = $this->fractalService->createData($resource);

            $response = array_merge(['success' => $success], $resource->toArray());
        }else{
            $response = [
                'success' => $success,
                'error' => ['message' => $resource]
            ];
        }

        return new JsonResponse($response,$code);

    }



}
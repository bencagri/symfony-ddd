<?php

namespace App\Project\App\Support;

use League\Fractal\Manager;
use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

class FractalService extends Manager
{

    /**
     * @var string
     */
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        parent::__construct();
    }

    /**
     * @param $resource
     * @param bool $success
     * @return array
     */
    public function transform($resource, $success = true)
    {
        if ($resource instanceof ResourceInterface) {

            $this->setSerializer(new JsonApiSerializer($this->baseUrl));

            //if there is an include, set transformer include
            if (isset($_GET['include']) && !empty($_GET['include'])) {
                $this->parseIncludes($_GET['include']);
            }

            $resource = $this->createData($resource);

            $response = array_merge(['success' => $success], $resource->toArray());
        }else {
            $response = [
                'success' => $success,
                'message' => $resource
            ];
        }

        return $response;

    }

    protected function paginatorAdapter()
    {
        $paginatorAdapter = new PagerfantaPaginatorAdapter($paginator, function(int $page) use ($request, $router) {
            $route = $request->attributes->get('_route');
            $inputParams = $request->attributes->get('_route_params');
            $newParams = array_merge($inputParams, $request->query->all());
            $newParams['page'] = $page;
            return $router->generate($route, $newParams, 0);
        });
    }

}

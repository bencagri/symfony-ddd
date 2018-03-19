<?php

namespace App\Aurora\App\Support;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

class FractalService extends Manager
{

    /**
     * @param $resource
     * @param bool $success
     * @return array
     */
    public function transform($resource, $success = true)
    {
        if ($resource instanceof ResourceInterface){

            $this->setSerializer(new JsonApiSerializer());

            //if there is an include, set transformer include
            if ( isset($_GET['include']) && !empty($_GET['include'])) {
                $this->parseIncludes($_GET['include']);
            }

            $resource = $this->createData($resource);

            $response = array_merge(['success' => $success], $resource->toArray());
        }else{
            $response = [
                'success' => $success,
                'error' => ['message' => $resource]
            ];
        }

        return $response;

    }

}

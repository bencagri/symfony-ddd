<?php

namespace App\Authorization\Controller;

use FOS\OAuthServerBundle\Controller\AuthorizeController;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OAuthAuthorizeController
 * @package App\Authorize\Controller
 *
 * {@inheritdoc}
 */
class OAuthAuthorizeController extends AuthorizeController {
    /**
     * Authorize user
     *
     * @Operation(
     *     tags={"OAuth"},
     *     summary="Authorize user",
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="formData",
     *         description="The redirect URI registered by the client",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="formData",
     *         description="The scope of the authorization",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="state",
     *         in="formData",
     *         description="Any client state that needs to be passed on to the client request URI",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function authorize(Request $request)
    {
        return parent::authorizeAction($request);
    }
}

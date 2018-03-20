<?php

namespace App\Authorization\Controller;

use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\OAuthServerBundle\Model\TokenInterface;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OAuthTokenController
 * @package App\Authorization\Controller
 *
 * {@inheritdoc}
 */
class OAuthTokenController extends TokenController {
    /**
     * Get access token
     * @param Request $request
     * @return TokenInterface
     *
     *
     * @Operation(
     *     tags={"OAuth"},
     *     summary="Get access token",
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="User name (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="User password (for `password` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="formData",
     *         description="Client Id (for `client_credentials` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="client_secret",
     *         in="formData",
     *         description="Client Secret (for `client_credentials` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *
     *     @SWG\Parameter(
     *         name="refresh_token",
     *         in="formData",
     *         description="The authorization code received by the authorization server(for `refresh_token` grant type`",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="formData",
     *         description="The authorization code received by the authorization server (For `authorization_code` grant type)",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="formData",
     *         description="If the `redirect_uri` parameter was included in the authorization request, and their values MUST be identical",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="formData",
     *         description="If the `redirect_uri` parameter was included in the authorization request, and their values MUST be identical",
     *         required=false,
     *         type="string"
     *     ),
     *
     *     @SWG\Parameter(
     *         name="grant_type",
     *         in="formData",
     *         description="refresh_token|authorization_code|password|client_credentials|custom",
     *         required=false,
     *         default="client_credentials",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     *
     */
    public function token(Request $request)
    {
        if ( ! $request->request->get('grant_type')){
            $request->request->set('grant_type','client_credentials');
        }

        return parent::tokenAction($request);
    }
}

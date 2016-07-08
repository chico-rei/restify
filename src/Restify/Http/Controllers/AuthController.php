<?php namespace ChicoRei\Packages\Restify\Http\Controllers;

use Auth;
use Exception;
use Input;
use JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends BaseController
{
    public function postLogin()
    {
        try
        {
            $credentials = Input::only(['email', 'password']);

            if (!$token = JWTAuth::attempt($credentials))
            {
                throw new UnauthorizedHttpException('', trans('restify.errors.forbidden'));
            }

            Auth::login(JWTAuth::toUser($token), Input::get('remember', false));

            return $this->responseFactory->create(compact('token'));
        } catch (Exception $e)
        {
            return $this->responseFactory->create($e);
        }
    }
}

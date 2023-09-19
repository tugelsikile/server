<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Auth\AuthRepository;
use App\Validations\Auth\AuthValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthRepository $repository;
    protected AuthValidation $validation;
    public function __construct()
    {
        $this->validation = new AuthValidation();
        $this->repository = new AuthRepository();
    }

    /* @
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        try {
            return responseFormat(200,'ok', $this->repository->me($request));
        } catch (Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
    /* @
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            return responseFormat(200,'ok', $this->repository->logout($request));
        } catch (Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
    /* @
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            return responseFormat(200,'ok', $this->repository->login($this->validation->login($request)));
        } catch (Exception $exception) {
            return responseFormat($exception->getCode(), $exception->getMessage());
        }
    }
}

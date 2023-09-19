<?php

namespace App\Repositories\Auth;

use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthRepository
{
    /* @
     * @param Request $request
     * @return mixed|null
     * @throws Exception
     */
    public function me(Request $request) {
        try {
            $response = null;
            if ($request->user() != null) $response = (new UserRepository())->table(new Request(['id' => $request->user()->id]))->first();
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
    /* @
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function logout(Request $request): bool
    {
        try {
            $user = $request->user();
            if ($user == null) $user = auth()->guard('api')->user();
            if ($user != null) $user->revoke();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
    /* @
     * @param Request $request
     * @return object
     * @throws Exception
     */
    public function login(Request $request): object
    {
        try {
            $response = (object) ['token' => null, 'data' => null];
            $user = User::where('email', $request['username'])->first();
            DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
            auth()->login($user);
            $response->token =  auth()->user()->createToken("web")->accessToken;
            $response->data = (new UserRepository())->table(new Request(['id' => $user->id]))->first();
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

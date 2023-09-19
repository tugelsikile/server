<?php

namespace App\Validations\Auth;

use App\Models\User\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthValidation
{
    /* @
     * @param Request $request
     * @return Request
     * @throws Exception
     */
    public function login(Request $request): Request
    {
        try {
            $valid = Validator::make($request->all(),[
                'username' => 'required|string|min:3|exists:users,email',
                'password' => [ 'required', 'min:6', function($attribute, $value, $fail) use ($request) {
                    if (User::where('email', $request['username'])->first() != null) {
                        if (! Hash::check($value, User::where('email', $request['username'])->first()->password)) {
                            return $fail(__('auth.failed'));
                        }
                    }
                    return true;
                } ]
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"), 400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

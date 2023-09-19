<?php

namespace App\Validations\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidation
{
    /* @
     * @param Request $request
     * @return Request
     * @throws Exception
     */
    public function create(Request $request): Request
    {
        try {
            $valid = Validator::make($request->all(),[
                'nama_pengguna' => 'required|string|min:1|max:199',
                'username' => 'required|string|min:3|max:199|unique:users,email',
                'kata_sandi' => 'nullable|string|min:6',
                'level_pengguna' => 'required|string|in:user,participant,super,admin',
                'jurusan' => 'required_if:level_pengguna,participant|exists:majors,id',
                'tingkat' => 'required_if:level_pengguna,participant|numeric|min:1|max:12',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }

    /* @
     * @param Request $request
     * @return Request
     * @throws Exception
     */
    public function update(Request $request): Request
    {
        try {
            $valid = Validator::make($request->all(),[
                'data_pengguna' => 'required|exists:users,id',
                'nama_pengguna' => 'required|string|min:1|max:199',
                'username' => 'required|string|min:3|max:199|unique:users,email,' . $request['data_pengguna'] . ',id',
                'kata_sandi' => 'nullable|string|min:6',
                'level_pengguna' => 'required|string|in:user,participant,super,admin',
                'jurusan' => 'required_if:level_pengguna,participant|exists:majors,id',
                'tingkat' => 'required_if:level_pengguna,participant|numeric|min:1|max:12',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }

    /* @
     * @param Request $request
     * @return Request
     * @throws Exception
     */
    public function delete(Request $request): Request
    {
        try {
            $valid = Validator::make($request->all(),[
                'data_pengguna' => 'required|exists:users,id',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

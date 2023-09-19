<?php

namespace App\Validations\Major;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorValidation
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
                'nama_jurusan' => 'required|string|min:1|max:199',
                'singkatan' => 'required|string|min:3|max:32|unique:majors,code',
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
                'data_jurusan' => 'required|exists:majors,id',
                'nama_jurusan' => 'required|string|min:1|max:199',
                'singkatan' => 'required|string|min:3|max:32|unique:majors,code,' . $request['data_jurusan'] . ',id',
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
                'data_jurusan' => 'required|exists:majors,id',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

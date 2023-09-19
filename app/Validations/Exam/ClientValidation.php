<?php

namespace App\Validations\Exam;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientValidation
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
                'ujian' => 'required|exists:exams,id',
                'nama_client' => 'required|string|min:1|unique:exam_clients,name,' . $request['ujian'] . ',exam',
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
                'ujian' => 'required|exists:exams,id',
                'data_client' => 'required|exists:exam_clients,id',
                'nama_client' => 'required|string|min:1|unique:exam_clients,name,' . $request['data_client'] . ',id,exam,' . $request['ujian'],
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
                'data_client' => 'required|exists:exam_clients,id',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

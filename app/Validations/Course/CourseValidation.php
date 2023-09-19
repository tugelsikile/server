<?php

namespace App\Validations\Course;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseValidation
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
                'nama_mata_pelajaran' => 'required|string|min:1|max:199',
                'jurusan' => 'nullable|exists:majors,id',
                'singkatan' => 'nullable|string|min:1|max:32',
                'tingkat' => 'required|numeric|min:1|max:12',
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
                'data_mata_pelajaran' => 'required|exists:courses,id',
                'nama_mata_pelajaran' => 'required|string|min:1|max:199',
                'jurusan' => 'nullable|exists:majors,id',
                'singkatan' => 'nullable|string|min:1|max:32',
                'tingkat' => 'required|numeric|min:1|max:12',
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
                'data_mata_pelajaran' => 'required|exists:courses,id',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

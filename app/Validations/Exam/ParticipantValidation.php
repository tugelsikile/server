<?php

namespace App\Validations\Exam;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParticipantValidation
{
    /* @
     * @param Request $request
     * @return Request
     * @throws Exception
     */
    public function create(Request $request): Request
    {
        try {
            $valid = Validator::make($request->all(), [
                'ujian' => 'required|exists:exams,id',
                'client' => 'required|exists:exam_clients,id',
                'peserta' => 'required|array|min:1',
                'peserta.*.nama_peserta' => 'required|exists:users,id,level,participant|distinct:peserta.*.nama_peserta',
                'peserta.*.nomor_peserta' => 'nullable',
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
            $valid = Validator::make($request->all(), [
                'ujian' => 'required|exists:exams,id',
                'client' => 'required|exists:exam_clients,id',
                'peserta' => 'required|array|min:1',
                'peserta.*.data_peserta' => 'required|exists:participants,id',
                'peserta.*.nama_peserta' => 'required|exists:users,id,level,participant|distinct:peserta.*.nama_peserta',
                'peserta.*.nomor_peserta' => 'nullable',
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
            $valid = Validator::make($request->all(), [
                'peserta' => 'required',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            if (is_array($request['peserta'])) {
                $valid = Validator::make($request->all(), [
                    'peserta' => 'array|min:1',
                    'peserta.*' => 'required|exists:exam_participants,id',
                ]);
                if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            } elseif (gettype($request['peserta']) == "string") {
                $valid = Validator::make($request->all(), [
                    'peserta' => 'required|exists:exam_participants,id',
                ]);
                if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            }
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

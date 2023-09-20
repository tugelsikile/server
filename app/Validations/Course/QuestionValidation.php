<?php

namespace App\Validations\Course;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionValidation
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
                'pembahasan' => 'required|exists:course_topics,id',
                'soal' => 'required|array|min:1',
                'soal.*.isi_soal' => 'required|string|min:0',
                'soal.*.jenis_soal' => 'required|string|in:multi_choice,string,lines,absolute',
                'soal.*.skor_minimal' => 'nullable|numeric|min:0',
                'soal.*.skor_maksimal' => 'nullable|numeric|min:0',
                'soal.*.kunci_jawaban' => 'nullable|array',
                'soal.*.kunci_jawaban.*.isi_kunci_jawaban' => 'required|string|min:0',
                'soal.*.kunci_jawaban.*.skor' => 'nullable|numeric|min:0',
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
                'pembahasan' => 'required|exists:course_topics,id',
                'soal' => 'required|array|min:1',
                'soal.*.data_soal' => 'nullable|exists:questions,id',
                'soal.*.isi_soal' => 'required|string|min:0',
                'soal.*.jenis_soal' => 'required|string|in:multi_choice,string,lines,absolute',
                'soal.*.skor_minimal' => 'nullable|numeric|min:0',
                'soal.*.skor_maksimal' => 'nullable|numeric|min:0',
                'soal.*.kunci_jawaban' => 'nullable|array',
                'soal.*.kunci_jawaban.*.data_kunci_jawaban' => 'nullable|exists:answers,id',
                'soal.*.kunci_jawaban.*.isi_kunci_jawaban' => 'required|string|min:0',
                'soal.*.kunci_jawaban.*.skor' => 'nullable|numeric|min:0',
                'hapus_soal' => 'nullable|array',
                'hapus_soal.*' => 'required|exists:questions,id',
                'hapus_kunci_jawaban' => 'nullable|array',
                'hapus_kunci_jawaban.*' => 'required|exists:answers,id',
            ]);
            if ($valid->fails()) throw new Exception(collect($valid->errors()->all())->join("\n"),400);
            return $request;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),400);
        }
    }
}

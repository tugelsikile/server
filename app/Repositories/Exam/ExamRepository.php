<?php

namespace App\Repositories\Exam;

use App\Models\Exam\Exam;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class ExamRepository
{
    /* @
     * @param Request $request
     * @return Collection
     * @throws Exception
     */
    public function table(Request $request): Collection
    {
        try {
            $response = collect();
            $exams = Exam::orderBy('name', 'asc');
            if ($request->has('id')) $exams = $exams->where('id', $request['id']);
            $exams = $exams->get(['id','name','description','random_answer','random_question','show_result']);
            foreach ($exams as $item) {
                $response->push((object) [
                    'value' => $item->id,
                    'label' => $item->name,
                    'meta' => (object) [
                        'description' => $item->description,
                        'show_result' => $item->show_result,
                        'random' => (object) [
                            'answer' => $item->random_answer,
                            'question' => $item->random_question,
                        ]
                    ]
                ]);
            }
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }

    /* @
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function store(Request $request) {
        try {
            if ($request->has('data_ujian')) {
                $exam = Exam::where('id', $request['data_ujian'])->first();
            } else {
                $exam = new Exam();
                $exam->id = Uuid::uuid4()->toString();
            }
            $exam->name = $request['nama_ujian'];
            $exam->description = '';
            if ($request->has('keterangan')) $exam->description = $request['keterangan'];
            $exam->save();

            return $this->table(new Request(['id' => $exam->id]))->first();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }

    /* @
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function delete(Request $request): bool
    {
        try {
            Exam::where('id', $request['data_ujian'])->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

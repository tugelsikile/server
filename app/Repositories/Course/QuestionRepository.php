<?php

namespace App\Repositories\Course;

use App\Models\Question\Answer;
use App\Models\Question\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class QuestionRepository
{
    /* @
     * @param Request $request
     * @return Collection
     * @throws Exception
     */
    public function store(Request $request): Collection
    {
        try {
            $id = collect();
            if ($request->has('soal')) {
                foreach ($request['soal'] as $questionNumber => $inputQuestion) {
                    if (array_key_exists('data_soal', $inputQuestion)) {
                        $question = Question::where('id', $inputQuestion['data_soal'])->first();
                        if ($question == null) {
                            $question = new Question();
                            $question->id = Uuid::uuid4()->toString();
                            $question->course_topic = $request['pembahasan'];
                            if ($request->user() != null) $question->created_by = $request->user()->id;
                        } else {
                            if ($request->user() != null) $question->updated_by = $request->user()->id;
                        }
                    } else {
                        $question = new Question();
                        $question->id = Uuid::uuid4()->toString();
                        $question->course_topic = $request['pembahasan'];
                        if ($request->user() != null) $question->created_by = $request->user()->id;
                    }
                    $question->number = $questionNumber + 1;
                    $question->content = $inputQuestion['isi_soal'];
                    $question->type = $inputQuestion['jenis_soal'];
                    $question->max_score = 1;
                    $question->min_score = 1;
                    if (array_key_exists('skor_minimal', $inputQuestion)) $question->min_score = $inputQuestion['skor_minimal'];
                    if (array_key_exists('skor_maksimal', $inputQuestion)) $question->max_score = $inputQuestion['skor_maksimal'];
                    $question->save();
                    $id->push($question->id);
                    if (array_key_exists('kunci_jawaban', $inputQuestion)) {
                        if (is_array($inputQuestion['kunci_jawaban'])) {
                            foreach ($inputQuestion['kunci_jawaban'] as $numberAnswer => $inputAnswer) {
                                if (array_key_exists('data_kunci_jawaban', $inputAnswer)) {
                                    $answer = Answer::where('id', $inputAnswer['data_kunci_jawaban'])->first();
                                    if ($answer == null) {
                                        $answer = new Answer();
                                        $answer->id = Uuid::uuid4()->toString();
                                        $answer->question = $question->id;
                                        if ($request->user() != null) $answer->created_by = $request->user()->id;
                                    } else {
                                        if ($request->user() != null) $answer->updated_by = $request->user()->id;
                                    }
                                } else {
                                    $answer = new Answer();
                                    $answer->id = Uuid::uuid4()->toString();
                                    $answer->question = $question->id;
                                    if ($request->user() != null) $answer->created_by = $request->user()->id;
                                }
                                $answer->number = $numberAnswer + 1;
                                $answer->content = $inputAnswer['isi_kunci_jawaban'];
                                $answer->score = 0;
                                if (array_key_exists('skor', $inputAnswer)) $answer->score = $inputAnswer['skor'];
                                $answer->save();
                            }
                        }
                    }
                }
            }
            return $this->table(new Request(['id' => $id]));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }

    /* @
     * @param Question $question
     * @return Collection
     * @throws Exception
     */
    private function answerCollection(Question $question): Collection
    {
        try {
            $response = collect();
            $answers = Answer::where('question', $question->id)->orderBy('number', 'asc')->get(['id','content','number','score']);
            foreach ($answers as $answer) {
                $response->push((object) [
                    'value' => $answer->id,
                    'label' => $answer->content,
                    'meta' => (object) [
                        'number' => (object) [
                            'integer' => $answer->number,
                            'string' => toStr($answer->number)
                        ],
                        'score' => $answer->score,
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
     * @return Collection
     * @throws Exception
     */
    public function table(Request $request): Collection
    {
        try {
            $response = collect();
            $questions = Question::orderBy('number', 'asc');
            if ($request->has('id')) {
                if (is_array($request['id'])) $questions = $questions->whereIn('id', $request['id']);
                if (gettype($request['id']) == "string") $questions = $questions->where('id', $request['id']);
            }
            if ($request->has('topic')) $questions = $questions->where('course_topic', $request['topic']);
            $questions = $questions->get(['id','content','course_topic','type','number','min_score','max_score']);
            foreach ($questions as $question) {
                $response->push((object) [
                    'value' => $question->id,
                    'label' => $question->content,
                    'meta' => (object) [
                        'topic' => (new TopicRepository())->table(new Request(['id' => $question->course_topic]))->first(),
                        'number' => $question->number,
                        'type' => $question->type,
                        'score' => (object) [
                            'min' => $question->min_score,
                            'max' => $question->max_score,
                        ],
                        'answer' => $this->answerCollection($question)
                    ]
                ]);
            }
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

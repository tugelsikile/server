<?php

namespace App\Repositories\Exam;

use App\Models\Exam\ExamParticipant;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class ParticipantRepository
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
            $participants = ExamParticipant::orderBy('exam_participants.user_code', 'asc')
                ->leftJoin('users', 'exam_participants.user', '=', 'users.id');
            if ($request->has('id')) {
                if (is_array($request['id']) && count($request['id']) > 0) $participants = $participants->whereIn('exam_participants.id', $request['id']);
                if (gettype($request['id']) == "string" && strlen($request['id']) > 0) $participants = $participants->where('exam_participants.id', $request['id']);
            }
            if ($request->has('exam') && strlen($request['exam']) > 0) $participants = $participants->where('exam_participants.exam', $request['exam']);
            if ($request->has('client') && strlen($request['client']) > 0) $participants = $participants->where('exam_participants.client', $request['client']);
            if ($request->has('keyword') && strlen($request['keyword']) > 0) {
                $keyword = $request['keyword'];
                $participants = $participants->where(function ($query) use ($keyword) {
                    $query->where('exam_participants.user_code', 'like', "%$keyword%")
                        ->orWhere('users.name', 'like', "%$keyword%");
                });
            }
            $participants = $participants->get(['exam_participants.id','exam_participants.client','exam_participants.user','exam_participants.user_code','users.name']);
            foreach ($participants as $participant) {
                $response->push((object) [
                    'value' => $participant->id,
                    'label' => $participant->name,
                    'meta' => (object) [
                        'code' => $participant->user_code,
                        'user' => (new UserRepository())->table(new Request(['id' => $participant->user]))->first(),
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
    public function store(Request $request): Collection
    {
        try {
            if ($request->has('peserta')) {
                foreach ($request['peserta'] as $input) {
                    $participant = ExamParticipant::where('user', $input['nama_peserta'])->first();
                    if (array_key_exists('data_peserta', $input)) $participant = ExamParticipant::where('id', $input['data_peserta'])->first();
                    if ($participant == null) {
                        $participant = new ExamParticipant();
                        $participant->id = Uuid::uuid4()->toString();
                        $participant->exam = $request['ujian'];
                        $participant->client = $request['client'];
                        $participant->user_code = generateParticipantCode($request['client']);
                        $participant->user = $input['nama_peserta'];
                    }
                    if (array_key_exists('nomor_peserta', $input)) $participant->user_code = $input['nomor_peserta'];
                    $participant->save();
                }
            }
            return $this->table(new Request(['exam' => $request['exam'], 'client' => $request['client']]));
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
            if ($request->has('peserta')) {
                if (is_array($request['peserta'])) {
                    ExamParticipant::whereIn('id', $request['peserta'])->delete();
                } elseif (gettype($request['peserta']) == "string") {
                    ExamParticipant::where('id', $request['peserta'])->delete();
                }
            }
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

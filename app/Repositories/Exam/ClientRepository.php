<?php

namespace App\Repositories\Exam;

use App\Models\Exam\ExamClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class ClientRepository
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
            $clients = ExamClient::orderBy('name','asc');
            if ($request->has('id')) $clients = $clients->where('id', $request['id']);
            if ($request->has('exam')) $clients = $clients->where('exam', $request['exam']);
            foreach ($clients->get(['id','name','code','token','meta']) as $client) {
                $response->push((object) [
                    'value' => $client->id,
                    'label' => $client->name,
                    'meta' => (object) [
                        'code' => $client->code,
                        'token' => $client->token,
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
            if ($request->has('data_client')) {
                $client = ExamClient::where('id', $request['data_client'])->first();
            } else {
                $client = new ExamClient();
                $client->id = Uuid::uuid4()->toString();
                $client->code = generateExamClientCode();
                $client->token = generateExamClientToken();
            }
            $client->name = $request['nama_client'];
            $client->exam = $request['ujian'];
            $client->save();
            return $this->table(new Request(['id' => $client->id]))->first();
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
            ExamClient::where('id', $request['data_client'])->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

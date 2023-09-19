<?php

namespace App\Repositories\Major;

use App\Models\Major\Major;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class MajorRepository
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
            $majors = Major::orderBy('name', 'asc');
            if ($request->has('id')) $majors = $majors->where('id', $request['id']);
            $majors = $majors->get(['id','name','code']);
            foreach ($majors as $major) {
                $response->push((object) [
                    'value' => $major->id,
                    'label' => $major->name,
                    'meta' => (object) [
                        'code' => $major->code,
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
            if ($request->has('data_jurusan')) {
                $major = Major::where('id', $request['data_jurusan'])->first();
            } else {
                $major = new Major();
                $major->id = Uuid::uuid4()->toString();
            }
            $major->code = $request['singkatan'];
            $major->name = $request['nama_jurusan'];
            $major->save();
            return $this->table(new Request(['id' => $major->id]))->first();
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
            Major::where('id', $request['data_jurusan'])->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

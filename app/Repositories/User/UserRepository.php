<?php

namespace App\Repositories\User;

use App\Models\User\User;
use App\Repositories\Major\MajorRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserRepository
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
            $users = User::orderBy('name', 'asc');
            if ($request->has('id')) $users = $users->where('id', $request['id']);
            if ($request->has('level')) $users = $users->where('level', $request['level']);
            if ($request->has('email')) $users = $users->where('email', $request['email']);
            $users = $users->get(['id','name','email','level','major','class_level']);
            foreach ($users as $user) {
                $major = null;
                if ($user->major != null) $major = (new MajorRepository())->table(new Request(['id' => $user->major]))->first();
                $response->push((object) [
                    'value' => $user->id,
                    'label' => $user->name,
                    'meta' => (object) [
                        'email' => $user->email,
                        'level' => $user->level,
                        'major' => $major,
                        'class_level' => $user->class_level
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
            if ($request->has('data_pengguna')) {
                $user = User::where('id', $request['data_pengguna'])->first();
                if (! $request->has('kata_sandi')) $user->password = Hash::make($request['username']);
            } else {
                $user = new User();
                $user->id = Uuid::uuid4()->toString();
                if ($request->has('kata_sandi') && strlen($request['kata_sandi']) > 0) {
                    $user->password = Hash::make($request['kata_sandi']);
                } else {
                    $user->password = Hash::make($request['username']);
                }
            }
            $user->name = $request['nama_pengguna'];
            $user->email = $request['username'];
            $user->level = $request['level_pengguna'];
            if ($request->has('jurusan')) $user->major = $request['jurusan'];
            if ($request->has('tingkat')) $user->class_level = $request['tingkat'];
            $user->save();
            return $this->table(new Request(['id' => $user->id]))->first();
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
            User::where('id', $request['data_pengguna'])->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

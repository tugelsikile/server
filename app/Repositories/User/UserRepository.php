<?php

namespace App\Repositories\User;

use App\Models\User\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
            $users = $users->get(['id','name','email','level']);
            foreach ($users as $user) {
                $response->push((object) [
                    'value' => $user->id,
                    'label' => $user->name,
                    'meta' => (object) [
                        'email' => $user->email,
                        'level' => $user->level,
                    ]
                ]);
            }
            return $response;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(),500);
        }
    }
}

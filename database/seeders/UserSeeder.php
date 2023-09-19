<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dumps = collect();
        $dumps->push((object) ['name' => 'Super Admin', 'username' => 'super@admin.com', 'password' => 'P4ssw0rd', 'level' => 'super']);

        $this->command->getOutput()->progressStart($dumps->count());
        foreach ($dumps as $dump) {
            $user = User::where('email', $dump->username)->first();
            if ($user == null) {
                $user = new User();
                $user->id = Uuid::uuid4()->toString();
                $user->name = $dump->name;
                $user->email = $dump->username;
                $user->password = Hash::make($dump->password);
                if (property_exists($dump,'level')) $user->level = $dump->level;
                $user->save();
            }
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            [
                'role' => 'admin',
                'email' => 'bphocuz@gmail.com',
                'name' => 'System Admin',
                'phone' => '0247867355',
                'password' => 'admin@123',
            ],
            [
                'role' => 'treasurer',
                'email' => 'roseboateng@gmail.com',
                'name' => 'Rose Boateng',
                'phone' => '0208165378',
                'password' => 'Rose@12345',
            ],
            [
                'role' => 'viewer',
                'email' => 'viewer@gmail.com',
                'name' => 'Transparency Viewer',
                'phone' => '0240000002',
                'password' => 'viewer12345',
            ],
        ];

        $keepIds = [];
        foreach ($accounts as $account) {
            $roleUser = User::where('role', $account['role'])->first();
            $emailUser = User::where('email', $account['email'])->first();

            if ($roleUser && $emailUser && $roleUser->id !== $emailUser->id) {
                $emailUser->delete();
            }

            if (! $roleUser && $emailUser) {
                $roleUser = $emailUser;
            }

            if (! $roleUser) {
                $roleUser = new User();
            }

            $roleUser->name = $account['name'];
            $roleUser->email = $account['email'];
            $roleUser->phone = $account['phone'];
            $roleUser->role = $account['role'];
            $roleUser->is_active = true;
            $roleUser->password = Hash::make($account['password']);
            $roleUser->save();

            $user = $roleUser;

            $keepIds[] = $user->id;
        }

        User::whereNotIn('id', $keepIds)->delete();
    }
}

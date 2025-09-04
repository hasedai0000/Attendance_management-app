<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
 /**
  * Run the database seeds.
  *
  * @return void
  */
 public function run()
 {
  User::create([
   'name' => '管理者',
   'email' => 'admin@example.com',
   'password' => Hash::make('password'),
   'is_admin' => true,
   'email_verified_at' => now(),
  ]);

  User::create([
   'name' => 'テストユーザー',
   'email' => 'user@example.com',
   'password' => Hash::make('password'),
   'is_admin' => false,
   'email_verified_at' => now(),
  ]);
 }
}

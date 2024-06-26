<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // \App\Models\User::factory(10)->create();

    User::create([
      'firstname' => 'root',
      'lastname' => 'root',
      'email' => 'root@user.com',
      'country_code' => '20',
      'mobile' => '10001000200',
      'password' => Hash::make('password'),
      'username' => 'rootuser',
      'profile_complete' => '1',
      'ua' => '1'
    ]);

    //   Admin::create([
    //     'name' => 'Abdullah',
    //     'email' => 'abdullah@gmail.com',
    //     'username' => 'abdullah',
    //     'password' => Hash::make('password'),
    //   ]);
  }
}

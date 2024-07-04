<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ///以下、、初期ユーザーを自分で追加する
        DB::table('users')->insert([
            [
                'username' => 'Atlas一郎',
                'mail' => 'atlas1@mail.com',
                'password' => bcrypt('atlas1')
            ]
        ]);
    }
}

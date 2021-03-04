<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = new \App\Models\User;
        $administrator->name = "Admin";
        $administrator->email = "admin@admin";
        $administrator->password = \Hash::make("1234567890");
        $administrator->save();
        $this->command->info("User Admin berhasil diinsert");
    }
}

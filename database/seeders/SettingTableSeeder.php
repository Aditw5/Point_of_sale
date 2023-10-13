<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'id' => 1,
            'company_name' => 'POS',
            'address' => 'JL. Ahmad Aquan',
            'phone' => '081121341242',
            'nota_type' => 1,
            'discont' => 5,
            'path_logo' => '/img/logo.png',
            'path_member_card' => '/img/member.png',
        ]);
    }
}

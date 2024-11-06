<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('type_operations')->insert([
            ['nom' => 'entrée'],
            ['nom' => 'sortie'],
        ]);
    }
}

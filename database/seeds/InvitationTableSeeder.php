<?php

use App\Models\Invitation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvitationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Invitation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        factory(Invitation::class, 100)->create();
    }
}

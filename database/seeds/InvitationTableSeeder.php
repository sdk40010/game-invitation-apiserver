<?php

use Illuminate\Database\Seeder;
use App\Models\Invitation;

class InvitationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Invitation::class, 40)->create();
    }
}

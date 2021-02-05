<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagmapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagmaps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('invitation_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('invitation_id')
                ->references('id')->on('invitations')
                ->onDelete('cascade');
            $table->foreign('tag_id')
                ->references('id')->on('tags')
                ->onDelete('cascade');
            $table->index('invitation_id');
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagmaps');
    }
}

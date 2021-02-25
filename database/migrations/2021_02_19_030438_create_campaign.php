<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('subject', 255);
            $table->text('message');
            $table->string('email_sender');
            $table->string('email_target');
            $table->integer('track_click')->nullable();
            $table->integer('track_open')->nullable();
            $table->timestamps();
        });
        Schema::table('campaign', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign');
    }
}

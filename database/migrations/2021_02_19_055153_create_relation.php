<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_subscribers', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->change();
            $table->foreign('group_id')->references('id')->on('group')
                ->onUpdate('cascade')->onDelete('cascade');
                
            $table->integer('subscribe_id')->unsigned()->change();
            $table->foreign('subscribe_id')->references('id')->on('subscribers')
                ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('relation');
    }
}

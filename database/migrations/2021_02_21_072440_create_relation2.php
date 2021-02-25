<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelation2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail', function (Blueprint $table) {
            $table->integer('from_id')->unsigned()->change();
            $table->foreign('from_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
                
            $table->integer('to_id')->unsigned()->change();
            $table->foreign('to_id')->references('id')->on('subscribers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('campaign_id')->unsigned()->change();
            $table->foreign('campaign_id')->references('id')->on('campaign')
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
        Schema::dropIfExists('mail');
    }
}

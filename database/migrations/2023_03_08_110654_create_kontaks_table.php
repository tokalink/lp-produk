<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kontaks', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->string('last_seen')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('new_chat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kontaks');
    }
}

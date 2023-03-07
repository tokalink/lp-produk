<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('device_id');
            $table->string('from_phone');
            $table->string('to_phone');
            $table->string('message')->nullable();
            $table->string('file')->nullable();
            $table->integer('status')->default(0);
            $table->string('type')->default('text');
            $table->dateTime('send_at')->nullable();
            $table->string('msgid')->nullable();
            $table->string('chat_id')->nullable();
            $table->string('chat_type')->nullable();
            $table->string('chat_title')->nullable();            
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
        Schema::dropIfExists('chats');
    }
}

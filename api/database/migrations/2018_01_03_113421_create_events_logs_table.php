<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->index();
            $table->string('model_type', 191)->index();
            $table->integer('user_id')->index();
            $table->integer('from_user_id')->nullable();
            $table->text('description')->nullable();
            $table->string('url_image')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('events_logs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->index();
            $table->string('model_type', 191)->index();
            $table->text('text')->nullable();
            $table->boolean('is_audio')->nullable();
            $table->string('user_name', 191);
            $table->integer('user_id');
            $table->integer('reply_id')->nullable()->index();
            $table->integer('status')->index();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('app_name', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

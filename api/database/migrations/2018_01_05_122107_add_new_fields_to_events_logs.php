<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToEventsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events_logs', function (Blueprint $table) {
            $table->integer('relation_model_id')->nullable()->after('model_type');
            $table->string('relation_model_type')->nullable()->after('relation_model_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_logs', function (Blueprint $table) {
            $table->dropColumn('relation_model_id');
            $table->dropColumn('relation_model_type');
        });
    }
}

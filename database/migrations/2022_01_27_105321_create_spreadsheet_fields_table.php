<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpreadsheetFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spreadsheet_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('validation_type');
            $table->boolean('is_special')->default(false);
            $table->integer('sequence')->default(-1);

            $table->foreignId('spreadsheet_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unique(['code', 'spreadsheet_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spreadsheet_fields');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('sn');
            $table->string('image')->nullable();
            $table->string('model')->nullable();
            $table->enum('class', ['A', 'B', 'C'])->nullable();
            $table->float('price')->nullable();
            $table->integer('ppm')->nullable()->comment('duration of maintenance each quarter 3,6,9');
            $table->enum('ppm_unit', ['Year', 'Month', 'Day'])->nullable();
            $table->boolean('need_calibration')->nullable();
            $table->string('calibration_cycle')->nullable();
            $table->foreignId('department_id')->constrained('departments');
     
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
        Schema::dropIfExists('equipment');
    }
};

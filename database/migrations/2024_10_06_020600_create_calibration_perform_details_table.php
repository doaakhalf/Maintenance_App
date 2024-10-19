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
        Schema::create('calibration_perform_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calibration_perform_id');
            $table->unsignedBigInteger('spare_part_id');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency');
            $table->string('warranty');
            $table->enum('warranty_unit', ['Year', 'Month'])->nullable();

            
            $table->foreign('calibration_perform_id')->references('id')->on('calibration_performs')->onDelete('cascade');
            $table->foreign('spare_part_id')->references('id')->on('spare_parts')->onDelete('cascade');

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
        Schema::dropIfExists('calibration_perform_details');
    }
};

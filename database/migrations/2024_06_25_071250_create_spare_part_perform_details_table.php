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
        Schema::create('spare_part_perform_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spare_part_perform_id');
            $table->unsignedBigInteger('spare_part_id');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->nullable();
            $table->string('currency')->nullable();
            $table->date('warranty')->nullable();
            $table->timestamps();

            $table->foreign('spare_part_perform_id')->references('id')->on('spare_part_performs')->onDelete('cascade');
            $table->foreign('spare_part_id')->references('id')->on('spare_parts')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spare_part_perform_details');
    }
};

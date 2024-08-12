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
        Schema::create('maintenance_perform_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_perform_id');
            $table->unsignedBigInteger('spare_part_id');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency');
            $table->string('warranty');

            
            $table->foreign('maintenance_perform_id')->references('id')->on('maintenance_performs')->onDelete('cascade');
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
        Schema::dropIfExists('maintenance_perform_details');
    }
};

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
        Schema::create('spare_part_perform_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spare_part_perform_id');
            $table->string('attachment');
        
            $table->foreign('spare_part_perform_id')->references('id')->on('spare_part_performs')->onDelete('cascade');
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
        Schema::dropIfExists('spare_part_perform_attachments');
    }
};

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
        Schema::create('calibration_request_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calibration_request_id');
            $table->unsignedBigInteger('assigned_by_id');
            $table->unsignedBigInteger('assigned_to_id');
           
            $table->timestamps();

            $table->foreign('calibration_request_id')->references('id')->on('calibration_requests')->onDelete('cascade');
            $table->foreign('assigned_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calibration_request_assignments');
    }
};

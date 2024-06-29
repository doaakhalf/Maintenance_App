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
        Schema::create('calibration_performs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calibration_request_id');
            $table->unsignedBigInteger('technician_id');
            $table->timestamp('perform_date')->useCurrent();
            $table->string('status')->default('Pending');
            $table->text('service_report')->nullable();
           
            $table->foreign('calibration_request_id')->references('id')->on('calibration_requests')->onDelete('cascade');
            $table->foreign('technician_id')->references('id')->on('users')->onDelete('cascade');
        
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
        Schema::dropIfExists('calibration_performs');
    }
};

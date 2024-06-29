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
        Schema::create('spare_part_performs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spare_part_request_id');
            $table->unsignedBigInteger('technician_id');
            $table->timestamp('perform_date')->useCurrent();
            $table->string('status')->default('Completed');
            $table->text('note')->nullable();
   

            $table->foreign('spare_part_request_id')->references('id')->on('spare_part_requests')->onDelete('cascade');
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
        Schema::dropIfExists('spare_part_performs');
    }
};

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
        Schema::create('spare_part_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('signed_to_id');

            $table->timestamp('request_date')->useCurrent();
            $table->string('status')->default('Pending');
            $table->longText('note');
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('signed_to_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
        Schema::create('spare_part_request_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spare_part_request_id');
            $table->unsignedBigInteger('spare_part_id');
            $table->integer('quantity')->nullable();
            // $table->decimal('price',10,2);
            // $table->string('currency');
            // $table->date('warranty');

            $table->timestamps();

            $table->foreign('spare_part_request_id')->references('id')->on('spare_part_requests')->onDelete('cascade');
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
        Schema::dropIfExists('spare_part_request_details');
        Schema::dropIfExists('spare_part_requests');
    }
};

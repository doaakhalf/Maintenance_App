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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->string('name')->nullable();;
            $table->enum('type', ['In', 'Out', 'Warranty']);
            $table->string('status')->default('Pending');
            $table->timestamp('request_date')->useCurrent();
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('signed_to_id');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('signed_to_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('maintenance_requests');
    }
};

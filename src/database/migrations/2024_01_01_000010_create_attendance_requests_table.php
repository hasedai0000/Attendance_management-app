<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRequestsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('attendance_requests', function (Blueprint $table) {
   $table->uuid('id')->primary();
   $table->uuid('attendance_id');
   $table->uuid('user_id');
   $table->time('requested_clock_in')->nullable();
   $table->time('requested_clock_out')->nullable();
   $table->text('requested_note')->nullable();
   $table->enum('status', ['pending', 'approved'])->default('pending');
   $table->timestamp('approved_at')->nullable();
   $table->uuid('approved_by')->nullable();
   $table->timestamps();

   $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
   $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
   $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
  });
 }

 /**
  * Reverse the migrations.
  *
  * @return void
  */
 public function down()
 {
  Schema::dropIfExists('attendance_requests');
 }
}

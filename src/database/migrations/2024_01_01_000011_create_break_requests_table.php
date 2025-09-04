<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreakRequestsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('break_requests', function (Blueprint $table) {
   $table->uuid('id')->primary();
   $table->uuid('attendance_request_id');
   $table->uuid('break_id')->nullable();
   $table->time('requested_break_start');
   $table->time('requested_break_end')->nullable();
   $table->timestamps();

   $table->foreign('attendance_request_id')->references('id')->on('attendance_requests')->onDelete('cascade');
   $table->foreign('break_id')->references('id')->on('breaks')->onDelete('set null');
  });
 }

 /**
  * Reverse the migrations.
  *
  * @return void
  */
 public function down()
 {
  Schema::dropIfExists('break_requests');
 }
}

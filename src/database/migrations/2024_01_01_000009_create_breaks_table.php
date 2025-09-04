<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreaksTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('breaks', function (Blueprint $table) {
   $table->uuid('id')->primary();
   $table->uuid('attendance_id');
   $table->time('break_start');
   $table->time('break_end')->nullable();
   $table->timestamps();

   $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
  });
 }

 /**
  * Reverse the migrations.
  *
  * @return void
  */
 public function down()
 {
  Schema::dropIfExists('breaks');
 }
}

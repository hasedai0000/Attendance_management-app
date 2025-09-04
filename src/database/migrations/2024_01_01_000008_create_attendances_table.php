<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('attendances', function (Blueprint $table) {
   $table->uuid('id')->primary();
   $table->uuid('user_id');
   $table->date('date');
   $table->time('clock_in')->nullable();
   $table->time('clock_out')->nullable();
   $table->text('note')->nullable();
   $table->enum('status', ['off_duty', 'working', 'break', 'finished'])->default('off_duty');
   $table->timestamps();

   $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
   $table->unique(['user_id', 'date']);
  });
 }

 /**
  * Reverse the migrations.
  *
  * @return void
  */
 public function down()
 {
  Schema::dropIfExists('attendances');
 }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 /**
  * Run the migrations.
  */
 public function up(): void
 {
  Schema::table('users', function (Blueprint $table) {
   // 既存のidカラムを削除して新しいUUIDカラムを追加
   $table->dropColumn('id');
  });

  Schema::table('users', function (Blueprint $table) {
   $table->uuid('id')->primary()->first();
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::table('users', function (Blueprint $table) {
   $table->dropColumn('id');
  });

  Schema::table('users', function (Blueprint $table) {
   $table->id()->primary();
  });
 }
};

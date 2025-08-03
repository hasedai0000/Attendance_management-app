<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Profile extends Model
{
 use HasFactory;

 protected $table = 'profiles';
 protected $keyType = 'string';
 public $incrementing = false;

 protected $fillable = [
  'user_id',
  'img_url',
  'postcode',
  'address',
  'building_name',
 ];

 protected static function boot()
 {
  parent::boot();

  static::creating(function ($model) {
   if (empty($model->id)) {
    $model->id = Str::uuid();
   }
  });
 }

 public function user()
 {
  return $this->belongsTo(User::class);
 }
}

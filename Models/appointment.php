<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class appointment extends Model
{
    use HasFactory;
    protected $fillable = ['appointment','expert_id','expert_email','appointment_id'];

    protected function serializeDate(DateTimeInterface $date){
    return $date->format('Y-m-d H:i:s');
   }
}

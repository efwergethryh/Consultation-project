<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvailableTimes extends Model
{
    use HasFactory;
    protected $fillable = ['AvailableTime','expert_id','status',];

    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }
}

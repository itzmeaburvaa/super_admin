<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCoordinator extends Model
{
   protected $fillable = ['club_id', 'name', 'photo'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}


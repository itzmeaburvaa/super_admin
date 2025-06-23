<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['club_id', 'event_name', 'description', 'date', 'time', 'image_path'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    // Accessor to get full image URL
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}

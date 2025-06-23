<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'club_name',
        'logo',
        'introduction',
        'mission',
        'staff_coordinator_name',
        'staff_coordinator_email',
        'staff_coordinator_photo',
        'year_started',
    ];

    public function studentCoordinators()
    {
        return $this->hasMany(StudentCoordinator::class);
    }
    public function events()
{
    return $this->hasMany(Event::class);
}

}

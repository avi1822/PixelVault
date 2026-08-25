<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $table = 'membership_plans';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'gaming_hours',
        'gaming_discount_percent',
        'status'
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'membership_plan_id');
    }
}

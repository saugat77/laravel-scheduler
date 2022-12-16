<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderRenewsubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','expiry_date_of_registration'
    ];
}

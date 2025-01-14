<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['id','vendor_code','address','contact_phone_1','contact_phone_2' ,'email','user_id'];

    public function users(): BelongsTo {
        return $this->belongsTo(User::class,'user_id','id');
    }
}

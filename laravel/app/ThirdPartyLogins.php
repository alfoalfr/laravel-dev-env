<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyLogins extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'third_party_logins';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_name', 'service_id' ,'service_token'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function Users()
    {
        return $this->belongsToMany(Users::class);
    }
}

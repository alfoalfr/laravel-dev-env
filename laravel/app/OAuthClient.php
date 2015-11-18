<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class OAuthClient extends Model
{
    //Constants
    const THIRD_PARTY_CLIENT = 'third-party-client';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function getClientInfoByName($name){
        $client = $this->where('name', '=', $name)->first();
        if($client != null){
            $clientInfo = new StdClass();
            $clientInfo->id = $client->id;
            $clientInfo->secret = $client->secret;
            return $clientInfo;
        }
        return false;
    }

}

<?php

namespace App;

use App\Configuration;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * Indicates if the timestamps are included.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 
    ];

    /**
     * Determine if the user is registering a restricted domain.
     * @return array
     */
    public static function allowed($domain)
    {
        // Get domain action config
        $config = Configuration::where('key', 'domain')->first();
        $action = is_null($config) ? 'blacklist' : $config->value;
        // Perform check based on action
        if ($action == 'blacklist') {
            // Blacklist
            $denied = static::where('name', $domain)->first();
            return is_null($denied) ? true : false;
        }
        // Whitelist
        $permitted = static::where('name', $domain)->first();
        return is_null($permitted) ? false : true;
    }
}

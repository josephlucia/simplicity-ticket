<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'configurations';

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
    	'key', 'value', 
    ];

    /**
     * Get the value of the configuration setting.
     *
     * @param string  $setting
     * @return string
     */
    public static function value($key)
    {
        // Get the configuration value from the DB.
        $configuration = static::where('key', $key)->first();
        // Return the value if present otherwise give default.
        return empty($configuration->value) ? config('app.'.$key) : $configuration->value;
    }

    /**
     * Create or update the key value pair.
     *
     * @param string  $key
     * @param string  $value
     * @return void
     */
    public static function set($key, $value)
    {
        // Get the setting value if it exists.
        $config = static::where('key', $key)->first();
        // Create or update the value.
        if(is_null($config)) {
            static::create([
                'key' => $key,
                'value' => $value
            ]);
        } else {
            $config->update(['value' => $value]);
        }   
    }

    /**
     * Determine if the mail settings are blank.
     *
     * @return boolean
     */
    public static function smtp()
    {
        if(env('MAIL_HOST') == '') {
            $config = static::set('send_email', null);
            return false;
        }
        return true;
    }
}

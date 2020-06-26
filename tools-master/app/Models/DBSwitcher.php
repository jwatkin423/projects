<?php
namespace App\Models;

use Illuminate\Http\Request;
use App;
use Log;

class DBSwitcher extends BaseModel {

    protected $fillable = ['database_environment'];

    /**
     * Return attribute for checkbox
     * true for production
     * false for development
     */
    public function getDatabaseEnvironmentAttribute($attr) {
        return is_null($attr) ? self::isProduction() : (bool) $attr;
    }

    static public function environment() {
        if (session()->has('_database_environment')) {
            $session_environment = session('_database_environment');
            if (in_array($session_environment, ['development', 'production'])) {
                Log::debug('returning: ' . $session_environment);
                return $session_environment;
            }
        } else {
            $environment = "development" == App::environment() ? "development" : "production";
            Log::debug("The session is not set, and the environment is: {$environment}");
            return $environment;
        }
    }

    static public function isProduction() {
        return self::environment() == "production";
    }

    static public function isDevelopment() {
        return self::environment() == "development";
    }

    static public function toggleEnvironment() {
        $env = self::isProduction() ? 'development' : 'production';
        Log::debug('The env is now this: ' . $env);
        session()->put('_database_environment', $env);
    }

    static public function applyEnvironment() {
        Log::debug(__FUNCTION__);
        config()->set('', self::environment());
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLog;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Log the user login
        $this->logUserLogin($request, $user);
    }

    /**
     * Log the user login
     */
    private function logUserLogin(Request $request, $user)
    {
        try {
            UserLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'browser' => $this->getBrowser(),
                'os' => $this->getOperatingSystem(),
                'device' => $this->getDeviceType(),
            ]);
        } catch (\Exception $e) {
            // Log silently if there's an error
        }
    }

    /**
     * Get browser name
     */
    private function getBrowser()
    {
        $userAgent = strtolower(request()->userAgent());
        
        if (strpos($userAgent, 'chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'edge') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'opera') !== false) {
            return 'Opera';
        } elseif (strpos($userAgent, 'trident') !== false) {
            return 'Internet Explorer';
        }
        
        return 'Unknown';
    }

    /**
     * Get operating system
     */
    private function getOperatingSystem()
    {
        $userAgent = strtolower(request()->userAgent());
        
        if (strpos($userAgent, 'windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'mac') !== false) {
            return 'MacOS';
        } elseif (strpos($userAgent, 'linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'iOS';
        }
        
        return 'Unknown';
    }

    /**
     * Get device type
     */
    private function getDeviceType()
    {
        $userAgent = strtolower(request()->userAgent());
        
        if (strpos($userAgent, 'iphone') !== false) {
            return 'Phone';
        } elseif (strpos($userAgent, 'android') !== false && strpos($userAgent, 'mobile') !== false) {
            return 'Phone';
        } elseif (strpos($userAgent, 'ipad') !== false || (strpos($userAgent, 'android') !== false && strpos($userAgent, 'mobile') === false)) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }

    /**
     * Log out user
     */
    public function logout(Request $request)
    {
        // Update logout time for the latest login
        if ($request->user()) {
            $lastLogin = UserLog::where('user_id', $request->user()->id)
                               ->where('action', 'login')
                               ->latest('login_at')
                               ->first();
            
            if ($lastLogin && !$lastLogin->logout_at) {
                $lastLogin->update([
                    'logout_at' => now(),
                ]);
            }
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}


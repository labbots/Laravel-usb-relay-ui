<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Config;
use Menu;

class MenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $secure = false;
        if (App::environment() === 'production' || App::environment() === 'development') {
            $secure = true;
        }

        $isUserLogged    = $request->user() != null;

        $displayMenu = $isUserLogged;

        Menu::make('LeftNavBar', function ($menu) use ($request, $secure, $displayMenu) {

            if ($displayMenu && $request->user()->hasRole(['administrator', 'user'])) {
                //$menu->add('Home', ['url' => 'home', 'secure' => $secure]);
                $menu->add('Relay', ['url' => 'relays', 'secure' => $secure]);
               
            }

        });

        Menu::make('RightNavBar', function ($menu) use ($request, $secure, $displayMenu) {

            if (is_null($request->user())) {
                $menu->add('Login', ['url' => 'login', 'secure' => $secure]);
            } else {
                if ($displayMenu && $request->user()->hasRole(['administrator'])) {
                    $menu->add('Users', ['url' => 'users', 'secure' => $secure]);
                }
                $menu->add($request->user()->name, ['url' => '#', 'secure' => $secure, 'nickname' => 'userAccount']);
                $menu->add('Profile', ['url' => 'manage_profile', 'parent' => $menu->userAccount->id, 'secure' => $secure])->divide()
                    ->prepend('<i class="fa fa-btn fa-user text-muted"></i> ');
                $menu->add('Logout', ['url' => 'logout', 'parent' => $menu->userAccount->id, 'secure' => $secure])
                    ->prepend('<i class="fa fa-btn fa-sign-out text-muted"></i> ');
            }
        });
        return $next($request);
    }
}

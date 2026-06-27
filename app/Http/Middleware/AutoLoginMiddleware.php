<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AutoLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Find or create a default user
            $user = User::first();
            
            if (!$user) {
                $user = User::create([
                    'name' => 'Super Admin',
                    'email' => 'admin@mbg.test',
                    'password' => bcrypt('password'),
                ]);
            }

            // Sync ALL roles to this user so they have full access
            $allRoles = Role::all();
            if ($allRoles->count() > 0) {
                $user->syncRoles($allRoles);
            }

            Auth::login($user);
        }

        return $next($request);
    }
}

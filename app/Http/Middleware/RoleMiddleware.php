<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role; // pastikan model Role ada

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // cari role id berdasarkan nama role di tabel roles
        $roleData = Role::where('role', $role)->first();

        // kalau role tidak ditemukan atau user bukan role itu
        if (!$roleData || $user->role_id != $roleData->id) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

<?php
namespace Modules\Circulation\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureLibraryAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->roles()->where('slug', 'admin')->exists()) {
            abort(403, 'Akses hanya untuk administrator perpustakaan.');
        }
        return $next($request);
    }
}

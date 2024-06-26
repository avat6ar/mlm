<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckStatus
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
    if (Auth::check()) {
      $user = auth()->user();
      if ($user->status && $user->ev && $user->sv && $user->tv) {
        return $next($request);
      } else {
        $user->status = true;
        $user->ev = true;
        $user->sv = true;
        $user->tv = true;
        $user->save();
        return $next($request);
      }
    }
    abort(403);
  }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSupplier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user === null ||
            ! method_exists($user, 'company') ||
            $user->company === null ||
            $user->company->supplier === null
        ) {
            throw ValidationException::withMessages([
                'supplier' => trans('apiMessages.forbidden'),
            ]);
        }

        // Eager load the supplier relationship to avoid N+1
        $user->loadMissing('company.supplier');

        // Store supplier_id in request for easy access
        $request->merge([
            'supplier_id' => (int) $user->company->supplier->getKey(),
        ]);

        return $next($request);
    }
}


<?php

namespace Webkul\ApiKey\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ApiKeyAbilityMiddleware
{
    /**
     * Map URI segments to normalized module names.
     */
    protected array $moduleMap = [
        'leads'         => 'leads',
        'contacts'      => 'contacts',
        'quotes'        => 'quotes',
        'qoutes'        => 'quotes',
        'products'      => 'products',
        'activities'    => 'activities',
        'activity'      => 'activities',
        'mails'         => 'mails',
        'mail'          => 'mails',
        'settings'      => 'settings',
        'setting'       => 'settings',
        'configuration' => 'configuration',
        'users'         => 'settings',
        'user'          => 'settings',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Try resolving authenticated user via request or sanctum guard
        $user = $request->user() ?: auth('sanctum')->user();

        // If not authenticated or not via Sanctum personal access token, pass through
        if (! $user || ! method_exists($user, 'currentAccessToken')) {
            return $next($request);
        }

        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        // If not using a PersonalAccessToken (e.g. stateful session), pass through
        if (! $token instanceof PersonalAccessToken) {
            return $next($request);
        }

        // If token has full wildcard access, allow immediately
        if ($token->can('*')) {
            return $next($request);
        }

        // Determine target module from path: /api/v1/{module}
        $module = $this->resolveModule($request);

        if (! $module) {
            // Unmapped path, allow through
            return $next($request);
        }

        $isRead = in_array(strtoupper($request->method()), ['GET', 'HEAD', 'OPTIONS']);
        $action = $isRead ? 'read' : 'write';
        $specificAbility = "{$module}:{$action}";

        // Token can have either full module access ("leads") or specific action ("leads:read" / "leads:write")
        if ($token->can($module) || $token->can($specificAbility)) {
            return $next($request);
        }

        return response()->json([
            'message'          => trans('api_key::app.admin.api-keys.unauthorized', ['ability' => $specificAbility]),
            'required_ability' => $specificAbility,
        ], 403);
    }

    /**
     * Resolve module name from request path.
     */
    protected function resolveModule(Request $request): ?string
    {
        $segments = $request->segments();

        // Look for segments following api/v1 or api
        $apiIndex = array_search('api', $segments);

        if ($apiIndex !== false && isset($segments[$apiIndex + 1])) {
            $candidate = $segments[$apiIndex + 1];

            // If segment is version (e.g. v1), check the next segment
            if (preg_match('/^v\d+$/i', $candidate) && isset($segments[$apiIndex + 2])) {
                $candidate = $segments[$apiIndex + 2];
            }

            $candidate = strtolower($candidate);

            return $this->moduleMap[$candidate] ?? $candidate;
        }

        return null;
    }
}

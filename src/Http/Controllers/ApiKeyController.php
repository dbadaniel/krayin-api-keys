<?php

namespace Webkul\ApiKey\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\ApiKey\DataGrids\ApiKeyDataGrid;
use Webkul\User\Repositories\UserRepository;

class ApiKeyController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ApiKeyDataGrid::class)->process();
        }

        $currentUser = auth()->guard('user')->user();
        $canManageAll = bouncer()->hasPermission('settings.other_settings.api_keys');

        $users = $canManageAll
            ? $this->userRepository->findWhere(['status' => 1], ['id', 'name', 'email'])
            : collect([$currentUser]);

        return view('api_key::index', compact('users', 'currentUser', 'canManageAll'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'name'            => ['required', 'string', 'max:100'],
            'user_id'         => ['nullable', 'integer', 'exists:users,id'],
            'permission_type' => ['nullable', 'string', 'in:all,custom'],
            'abilities'       => ['nullable', 'array'],
            'abilities.*'     => ['string', 'max:50'],
        ]);

        $currentUser = auth()->guard('user')->user();
        $canManageAll = bouncer()->hasPermission('settings.other_settings.api_keys');

        // Determine target user
        if ($canManageAll && request()->filled('user_id')) {
            $targetUser = $this->userRepository->find(request('user_id'));
        } else {
            $targetUser = $currentUser;
        }

        if (! $targetUser) {
            return new JsonResponse([
                'message' => trans('api_key::app.admin.api-keys.not-found'),
            ], 404);
        }

        // Determine abilities
        $abilities = ['*'];

        if (request('permission_type') === 'custom' && is_array(request('abilities')) && count(request('abilities')) > 0) {
            $abilities = array_values(array_unique(array_filter(request('abilities'))));
        }

        $token = $targetUser->createToken(request('name'), $abilities);

        return new JsonResponse([
            'plain_text_token' => $token->plainTextToken,
            'token'            => [
                'id'         => $token->accessToken->id,
                'name'       => $token->accessToken->name,
                'abilities'  => $token->accessToken->abilities,
                'user_name'  => $targetUser->name,
                'created_at' => core()->formatDate($token->accessToken->created_at, 'd/m/Y H:i'),
            ],
            'message'          => trans('api_key::app.admin.api-keys.create-success'),
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = auth()->guard('user')->user();

        $token = $user->tokens()->find($id);

        if (! $token && bouncer()->hasPermission('settings.other_settings.api_keys')) {
            $token = PersonalAccessToken::find($id);
        }

        if (! $token) {
            return new JsonResponse([
                'message' => trans('api_key::app.admin.api-keys.not-found'),
            ], 404);
        }

        $token->delete();

        return new JsonResponse([
            'message' => trans('api_key::app.admin.api-keys.delete-success'),
        ], 200);
    }
}

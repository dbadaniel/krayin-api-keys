<?php

namespace Webkul\ApiKey\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\ApiKey\DataGrids\ApiKeyDataGrid;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ApiKeyDataGrid::class)->process();
        }

        return view('api_key::index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'name' => ['required', 'string', 'max:100'],
        ]);

        $user = auth()->guard('user')->user();

        $token = $user->createToken(request('name'));

        return new JsonResponse([
            'plain_text_token' => $token->plainTextToken,
            'token'            => [
                'id'         => $token->accessToken->id,
                'name'       => $token->accessToken->name,
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

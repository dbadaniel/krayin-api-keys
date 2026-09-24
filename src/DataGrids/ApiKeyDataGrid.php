<?php

namespace Webkul\ApiKey\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ApiKeyDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('personal_access_tokens')
            ->leftJoin('users', function ($join) {
                $join->on('personal_access_tokens.tokenable_id', '=', 'users.id')
                    ->where('personal_access_tokens.tokenable_type', '=', 'Webkul\\User\\Models\\User');
            })
            ->addSelect(
                'personal_access_tokens.id',
                'personal_access_tokens.name',
                'personal_access_tokens.abilities',
                'personal_access_tokens.last_used_at',
                'personal_access_tokens.created_at',
                'users.name as user_name',
                'users.email as user_email'
            );

        $this->addFilter('id', 'personal_access_tokens.id');
        $this->addFilter('name', 'personal_access_tokens.name');
        $this->addFilter('user_name', 'users.name');

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('personal_access_tokens.tokenable_id', $userIds);
        }

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('api_key::app.admin.datagrid.id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('api_key::app.admin.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('api_key::app.admin.datagrid.user'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => $row->user_name
                ? e($row->user_name) . ($row->user_email ? ' <span class="text-xs text-gray-500">(' . e($row->user_email) . ')</span>' : '')
                : '-',
        ]);

        $this->addColumn([
            'index'      => 'abilities',
            'label'      => trans('api_key::app.admin.datagrid.permissions'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => function ($row) {
                $raw = $row->abilities;
                $abilities = is_string($raw) ? json_decode($raw, true) : (array) $raw;

                if (empty($abilities) || in_array('*', $abilities)) {
                    return '<span class="badge badge-round badge-success">' . trans('api_key::app.admin.datagrid.all') . '</span>';
                }

                // Extract unique modules
                $modules = [];
                foreach ($abilities as $ability) {
                    $parts = explode(':', $ability);
                    $modules[] = ucfirst($parts[0]);
                }
                $uniqueModules = array_values(array_unique($modules));

                $label = implode(', ', array_slice($uniqueModules, 0, 3));
                if (count($uniqueModules) > 3) {
                    $label .= ' +' . (count($uniqueModules) - 3);
                }

                return '<span class="badge badge-round badge-primary" title="' . e(implode(', ', $abilities)) . '">' . e($label) . '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'last_used_at',
            'label'      => trans('api_key::app.admin.datagrid.last-used-at'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
            'closure'    => fn ($row) => $row->last_used_at
                ? core()->formatDate($row->last_used_at, 'd/m/Y H:i')
                : trans('api_key::app.admin.datagrid.never-used'),
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('api_key::app.admin.datagrid.created-at'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => core()->formatDate($row->created_at, 'd/m/Y H:i'),
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => trans('api_key::app.admin.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.settings.api_keys.delete', $row->id),
        ]);
    }
}

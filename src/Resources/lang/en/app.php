<?php

return [
    'acl' => [
        'title' => 'API Keys',
    ],

    'admin' => [
        'menu' => [
            'api-keys' => 'API Keys',
        ],

        'api-keys' => [
            'index' => [
                'title'       => 'API Keys / Personal Tokens',
                'create-btn'  => 'Create API Key',
                'description' => 'Manage your Personal Access Tokens to authenticate with the Krayin REST API without exposing your personal password.',
            ],

            'create' => [
                'title'            => 'Generate New API Key',
                'name'             => 'Key Name / Integration',
                'name-placeholder' => 'e.g., n8n Automation, Zapier, Webhook Integration',
                'user'             => 'Associated User / Login',
                'user-help'        => 'The key will operate under this user\'s identity and permissions.',
                'permissions'      => 'API Key Permissions',
                'all-permissions'  => 'Full Access (All actions allowed)',
                'custom-permissions' => 'Custom Permissions (Limit scopes)',
                'select-all'       => 'Select All',
                'deselect-all'     => 'Deselect All',
                'read'             => 'Read (GET)',
                'write'            => 'Write (POST/PUT/DELETE)',
                'save-btn'         => 'Generate Key',
                'cancel-btn'       => 'Cancel',
                'modules'          => [
                    'leads'         => 'Leads',
                    'contacts'      => 'Contacts (Persons & Organizations)',
                    'quotes'        => 'Quotes',
                    'products'      => 'Products',
                    'activities'    => 'Activities',
                    'mails'         => 'Emails',
                    'settings'      => 'Settings',
                    'configuration' => 'Advanced Configuration',
                ],
            ],

            'created' => [
                'title'            => 'API Key Generated Successfully',
                'warning'          => 'Make sure to copy your API key now. For your security, it will NOT be shown again!',
                'copy-btn'         => 'Copy Key',
                'copied'           => 'Copied to clipboard!',
                'close-btn'        => 'I Have Copied the Key',
                'how-to-use'       => 'How to use this key:',
                'how-to-use-desc'  => 'Add this header to your HTTP requests in your integration tool (e.g., n8n, Postman, Zapier):',
            ],

            'create-success' => 'API Key created successfully.',
            'delete-success' => 'API Key revoked successfully.',
            'not-found'      => 'API Key not found or already revoked.',
            'unauthorized'   => 'This API Key does not have permission (:ability) to perform this operation.',
        ],

        'datagrid' => [
            'id'           => 'ID',
            'name'         => 'Key Name',
            'user'         => 'User / Login',
            'permissions'  => 'Permissions',
            'all'          => 'Full Access',
            'custom'       => 'Custom (:count scopes)',
            'last-used-at' => 'Last Used',
            'created-at'   => 'Created At',
            'never-used'   => 'Never used',
            'delete'       => 'Revoke Key',
        ],
    ],
];

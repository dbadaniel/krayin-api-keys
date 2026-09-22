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
                'save-btn'         => 'Generate Key',
                'cancel-btn'       => 'Cancel',
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
        ],

        'datagrid' => [
            'id'           => 'ID',
            'name'         => 'Key Name',
            'user'         => 'Created By',
            'last-used-at' => 'Last Used',
            'created-at'   => 'Created At',
            'never-used'   => 'Never used',
            'delete'       => 'Revoke Key',
        ],
    ],
];

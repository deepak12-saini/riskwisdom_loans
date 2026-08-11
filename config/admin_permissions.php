<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin panel permission catalog
    |--------------------------------------------------------------------------
    |
    | Keys are checked via User::canAdmin($permission).
    | Admin role always has full access.
    | Staff users store an explicit allow-list in users.permissions.
    |
    */

    'catalog' => [
        'enquiries.view' => 'View enquiries / call leads',
        'enquiries.export' => 'Export enquiries CSV',
        'enquiries.convert' => 'Create client file from lead',
        'enquiries.delete' => 'Delete enquiries',
        'clients.view' => 'View client files',
        'clients.create' => 'Create client files',
        'clients.update' => 'Edit client files',
        'clients.archive' => 'Archive / restore client files',
        'tasks.view' => 'View tasks',
        'tasks.manage' => 'Create / update / close tasks',
        'tasks.delete' => 'Delete tasks',
        'documents.view' => 'View / download documents',
        'documents.manage' => 'Upload / send for e-sign / sync',
        'documents.delete' => 'Delete documents',
        'users.manage' => 'Manage staff users',
    ],

    /*
    | Default checked permissions when creating a new staff user.
    */
    'presets' => [
        'staff' => [
            'enquiries.view',
            'enquiries.export',
            'enquiries.convert',
            'clients.view',
            'clients.create',
            'clients.update',
            'tasks.view',
            'tasks.manage',
            'documents.view',
            'documents.manage',
        ],
    ],

    /*
    | Permissions that staff can never be assigned.
    */
    'admin_only' => [
        'users.manage',
    ],

    'roles' => [
        'admin' => 'Admin',
        'staff' => 'Staff',
    ],

    'role_descriptions' => [
        'admin' => 'Full access to the panel, including adding staff and deleting records.',
        'staff' => 'Custom access — choose exactly what this employee can do below.',
    ],

    'groups' => [
        'enquiries' => 'Leads & enquiries',
        'clients' => 'Client files',
        'tasks' => 'Tasks',
        'documents' => 'Documents & e-sign',
        'users' => 'User management',
    ],
];

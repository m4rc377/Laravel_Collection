<?php

// Database seeder data

return [
    'document_types' => ['Contract', 'License Agreement', 'EULA', 'Other'],
    'task_statuses' => ['Not Started', 'Started', 'Completed', 'Cancelled'],
    'task_types' => ['Task', 'Meeting', 'Phone call'],
    'contact_status' => ['Lead', 'Opportunity', 'Customer', 'Close'],
    'settings' => ['crm_email' => 'noreply@mini-crm.com'],
    'initial_permissions' => [
        'create_contact', 'edit_contact', 'delete_contact', 'list_contacts', 'view_contact',
        'create_document', 'edit_document', 'delete_document', 'list_documents', 'view_document',
        'create_task', 'edit_task', 'delete_task', 'list_tasks', 'view_task', 'edit_profile'
    ],
    'default_role' => 'administrator'
];
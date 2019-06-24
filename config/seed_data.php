<?php

// Database seeder data

return [
    'document_types' => ['Contract', 'License Agreement', 'EULA', 'Other'],
    'task_statuses' => ['Not Started', 'Started', 'Completed', 'Cancelled'],
    'task_types' => ['Task', 'Meeting', 'Phone call'],
    'contact_status' => ['Lead', 'Opportunity', 'Customer', 'Close'],
    'settings' => ['crm_email' => 'noreply@mini-crm.com', 'enable_email_notification' => 1],
    'permissions' => [
        'create_contact', 'edit_contact', 'delete_contact', 'list_contacts', 'view_contact', 'assign_contact',
        'create_document', 'edit_document', 'delete_document', 'list_documents', 'view_document', 'assign_document',
        'create_task', 'edit_task', 'delete_task', 'list_tasks', 'view_task', 'assign_task', 'update_task_status', 'edit_profile'
    ],
    'mailbox_folders' => array(
        array("title"=>"Inbox", "icon" => "fa fa-inbox"),
        array("title"=>"Sent", "icon" => "fa fa-envelope-o"),
        array("title"=>"Drafts", "icon" => "fa fa-file-text-o"),
        array("title"=>"Trash", "icon" => "fa fa-trash-o")
    )
];
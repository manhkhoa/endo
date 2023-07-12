<?php

return [
    'company' => 'Company',
    'companies' => 'Companies',
    'props' => [

    ],
    'department' => [
        'department' => 'Department',
        'departments' => 'Departments',
        'module_title' => 'Manage all Departments',
        'module_description' => 'Departments are division of your company dealing with a specific area of activity.',
        'module_example' => 'Admin, Finance, Human Resource are some examples of Departments.',
        'props' => [
            'name' => 'Name',
            'alias' => 'Alias',
            'description' => 'Description',
        ],
    ],
    'designation' => [
        'designation' => 'Designation',
        'designations' => 'Designations',
        'module_title' => 'Manage all Designations',
        'module_description' => 'Designations are the official job titles given to employees of your company.',
        'module_example' => 'Chief Executive Officer, Director, Manager are some examples of Designations.',
        'props' => [
            'name' => 'Name',
            'alias' => 'Alias',
            'parent' => 'Parent',
            'description' => 'Description',
        ],
    ],
    'branch' => [
        'branch' => 'Branch',
        'branches' => 'Branches',
        'module_title' => 'Manage all Branches',
        'module_description' => 'Branches are the location, where a business of your company is conducted.',
        'module_example' => 'Head Office, Regional Office, Branch Office are some example of Branches.',
        'props' => [
            'name' => 'Name',
            'alias' => 'Alias',
            'code' => 'Code',
            'parent' => 'Parent',
            'description' => 'Description',
        ],
    ],
];

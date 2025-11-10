<?php
return [
    //notification
    'notification' => [
        'success' => [
            'default_title' => "Success",
            'default_body' => "Action Success!"
        ]
    ],
    //category
    'category' => [
        'navigation' => [
            'label' => "Category",
            'model_label' => "Category",
            'plural_model_label' => "Category"
        ],
        'fields' => [
            'name' => "Name",
            'slug' => "Slug",
            'parent_category' => "Parent",
            'description' => "Description",
            'status' => "Status"
        ],
        'action' => [
            'toggle' => "Toggle",
        ]
    ]
];

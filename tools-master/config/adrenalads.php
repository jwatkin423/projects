<?php

return [
    "roles" => [
        "advertiser" => "Advertiser",
        "admin" => "Admin",
        "super_admin"=>"Super Admin"
    ],
    "avatars" => [
        "default" => "avatar1.png",
        "scan_dir" => "true",
        "base_dir" => public_path()."/images/avatars/",

        //Remove trailing slash
        "url" => "/images/avatars/",

        //Set this incase scan_dir is false
        "options" => []
    ]
];
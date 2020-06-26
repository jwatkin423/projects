<?php
namespace App\Helpers;

class Avatars {

    function avatar_url($avatar) {
        return url( config('adrenalads.avatars.url') . "/" . $avatar);
    }

    function get_avatars() {
        $avatars = [];

        if (config('adrenalads.avatars.scan_dir')) {
            $avatars = scandir(config('adrenalads.avatars.base_dir'));

            $avatars = array_map(function($path) {
                if (!preg_match('/^[\.]{1,2}$/', $path) && !preg_match('/^[\.]{1}[a-z\-_]*/i', $path)) {
                    return basename($path);
                }
            }, $avatars);
        } elseif (is_array(config('adrenalads.avatars.options'))) {
            $avatars = config('adrenalads.avatars.options');
        }

        foreach($avatars as $index => $avatar) {
            if ($avatar === null) {
                unset($avatars[$index]);
            }
        }

        return $avatars;
    }

}

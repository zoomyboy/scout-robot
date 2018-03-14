<?php

namespace App\Traits;

trait GeneratesBlade
{
    public function generateView($str, $params)
    {
        file_put_contents(resource_path('views/temp/string.blade.php'), $str);
        return (string) view()->make('temp.string', $params);
    }
}

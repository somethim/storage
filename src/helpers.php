<?php

if (! function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

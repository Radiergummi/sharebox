<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use function explode;

class FileBrowserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $path
     *
     * @return array
     */
    public function contents(string $path = '/')
    {
        $directories = Storage::disk('remote')->directories($path);
        $files       = Storage::disk('remote')->files($path);

        return [
            'path'        => $path,
            'segments'    => explode('/', $path),
            'directories' => $directories,
            'files'       => $files
        ];
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public function meta(string $path = '/')
    {
        if (Storage::disk('remote')->exists('/' . $path)) {
            $filesize     = Storage::disk('remote')->size($path);
            $fileModified = Storage::disk('remote')->lastModified($path);

            return [
                'path'         => $path,
                'size'         => $filesize,
                'lastModified' => $fileModified * 1000
            ];
        }

        return response($path . ' not found', 404);
    }

    /**
     * @param string $path
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function file(string $path = '/')
    {
        return Storage::disk('remote')->get($path);
    }
}

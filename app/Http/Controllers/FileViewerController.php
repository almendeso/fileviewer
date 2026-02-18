<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

class FileViewerController extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $baseDir = config('fileviewer.base_dir');
        $allowedRoots = config('fileviewer.allowed_roots');

        $path = $request->get('path', '');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');

        $currentDir = realpath($baseDir . '/' . $path);

        if (!$currentDir || !$this->isAllowedPath($currentDir, $allowedRoots)) {
            $currentDir = $baseDir;
            $path = '';
        }

        if ($request->has('download')) {
            $file = basename($request->get('download'));
            $target = realpath($currentDir . '/' . $file);
            if ($target && $this->isAllowedPath($target, $allowedRoots)) {
                return response()->download($target)->withHeaders([
                    'Access-Control-Allow-Origin'  => '*',
                    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                    'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                    'Access-Control-Expose-Headers'=> 'Content-Disposition, Content-Length',
                ]);
            }
            abort(403);
        }

        if ($request->has('download_folder')) {
            $folder = basename($request->get('download_folder'));
            $folderPath = realpath($currentDir . '/' . $folder);

            if (!$folderPath || !$this->isAllowedPath($folderPath, $allowedRoots)) abort(403);

            $zipPath = storage_path('app/tmp_' . uniqid() . '.zip');
            $zip = new ZipArchive();
            $zip->open($zipPath, ZipArchive::CREATE);

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $real = realpath($file->getPathname());
                    if ($this->isAllowedPath($real, $allowedRoots)) {
                        $zip->addFile($real, substr($real, strlen($folderPath) + 1));
                    }
                }
            }

            $zip->close();
            return response()->download($zipPath)
                ->withHeaders([
                    'Access-Control-Allow-Origin'  => '*',
                    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                    'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                    'Access-Control-Expose-Headers'=> 'Content-Disposition, Content-Length',
                ])->deleteFileAfterSend(true);
        }

        $items = collect();

        if (is_dir($currentDir) && is_readable($currentDir)) {

            $list = @scandir($currentDir);

            if ($list !== false) {

                $items = collect($list)
                    ->reject(fn($i) => $i === '.' || $i === '..' || str_starts_with($i, '.'))
                    ->map(function ($item) use ($currentDir) {

                        $fullPath = $currentDir . '/' . $item;

                        return [
                            'name'   => $item,
                            'path'   => $fullPath,
                            'is_dir' => is_dir($fullPath),
                            'size'   => (is_file($fullPath) && is_readable($fullPath))
                                ? filesize($fullPath)
                                : null,
                            'mtime'  => is_readable($fullPath)
                                ? @filemtime($fullPath)
                                : null,
                        ];
                    });
            }
        }

        $items = $items->sort(function ($a, $b) use ($sort, $order) {

            // 🔹 Pastas sempre no topo (como Apache)
            if ($a['is_dir'] && !$b['is_dir']) return -1;
            if (!$a['is_dir'] && $b['is_dir']) return 1;

            switch ($sort) {
                case 'size':
                    $valA = $a['size'];
                    $valB = $b['size'];
                    break;

                case 'date':
                    $valA = $a['mtime'];
                    $valB = $b['mtime'];
                    break;

                default: // name
                    $valA = strtolower($a['name']);
                    $valB = strtolower($b['name']);
            }

            if ($valA == $valB) return 0;

            if ($order === 'asc') {
                return ($valA < $valB) ? -1 : 1;
            } else {
                return ($valA > $valB) ? -1 : 1;
            }
        })->values();

        return view('index', compact('items', 'path'));
    }

    private function isAllowedPath($path, $roots)
    {
        $real = realpath($path);
        foreach ($roots as $root) {
            if ($real && str_starts_with($real, $root)) return true;
        }
        return false;
    }
}

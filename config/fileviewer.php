<?php

$baseDirEnv = env('FILEVIEWER_BASE_DIR');
$baseDir = $baseDirEnv
    ? realpath($baseDirEnv)
    : realpath(storage_path('app/uploads'));

if (!$baseDir) {
    throw new RuntimeException(
        'FILEVIEWER_BASE_DIR inválido ou diretório não existe'
    );
}

$allowedRoots = [];

$rawRoots = env('FILEVIEWER_ALLOWED_ROOTS', '');

foreach (explode(',', $rawRoots) as $root) {
    $root = trim($root);
    if (!$root) continue;

    // Caminho absoluto
    if (str_starts_with($root, '/')) {
        $real = realpath($root);
    } else {
        // Caminho relativo ao projeto
        $real = realpath(base_path($root));
    }

    if ($real) {
        $allowedRoots[] = $real;
    }
}

return [
    'base_dir'      => $baseDir,
    'allowed_roots' => $allowedRoots,
];

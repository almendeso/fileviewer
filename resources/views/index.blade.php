<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Backup Viewer</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>

@include('css')

<body>

<h2>📁 {{ $path ?: '/' }}</h2>

{{-- Botão Voltar --}}
@if($path)
    @php
        $parent = dirname($path);
        if ($parent === '.') $parent = '';
    @endphp
    <p>
        ← <a href="?path={{ $parent }}">Voltar</a>
    </p>
@endif

@php
    $currentSort  = request('sort', 'name');
    $currentOrder = request('order', 'asc');

    function sortLink($label, $column, $path, $currentSort, $currentOrder) {
        $newOrder = ($currentSort === $column && $currentOrder === 'asc')
            ? 'desc'
            : 'asc';

        return '<a href="?path='.$path.'&sort='.$column.'&order='.$newOrder.'">'.$label.'</a>';
    }
@endphp

<div class="table-wrapper">
<table>
    <thead>
        <tr>
            <th class="col-name">
                {!! sortLink('NOME', 'name', $path, $currentSort, $currentOrder) !!}
            </th>
            <th class="col-size">
                {!! sortLink('TAMANHO', 'size', $path, $currentSort, $currentOrder) !!}
            </th>
            <th class="col-date">
                {!! sortLink('MODIFICADO', 'date', $path, $currentSort, $currentOrder) !!}
            </th>
            <th class="col-actions"></th>
        </tr>
    </thead>
    <tbody>

    @foreach($items as $item)
        <tr>
            @if($item['is_dir'])
                <td class="col-name">
                    📁
                    <a href="?path={{ trim($path.'/'.$item['name'],'/') }}">
                        {{ $item['name'] }}
                    </a>
                </td>
                <td class="col-size">-</td>
                <td class="col-date">
                    {{ date('d/m/Y H:i', $item['mtime']) }}
                </td>
                <td class="col-actions">
                    <a class="icon"
                       title="Download pasta"
                       href="?path={{ $path }}&download_folder={{ $item['name'] }}">
                        📦
                    </a>
                </td>
            @else
                <td class="col-name">
                    📄 {{ $item['name'] }}
                </td>
                <td class="col-size">
                    {{ number_format($item['size'] / 1024, 2) }} KB
                </td>
                <td class="col-date">
                    {{ date('d/m/Y H:i', $item['mtime']) }}
                </td>
                <td class="col-actions">
                    <a class="icon"
                       title="Download"
                       href="?path={{ $path }}&download={{ $item['name'] }}">
                        ⬇️
                    </a>
                </td>
            @endif
        </tr>
    @endforeach

    </tbody>
</table>
</div>

</body>
</html>

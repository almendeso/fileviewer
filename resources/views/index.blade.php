<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Backup Viewer</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>

@include('css.app')

<body>

@include('layouts.app')

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

        $search = request('search');

        $query = http_build_query([
            'path'  => $path,
            'sort'  => $column,
            'order' => $newOrder,
            'search'=> $search,
        ]);

        return '<a href="?'.$query.'">'.$label.'</a>';
    }
@endphp

{{-- Barra de busca --}}
<br/>
<div class="toolbar">
    <form method="get" class="search-form">
        <input type="hidden" name="path" value="{{ $path }}">
        <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
        <input type="hidden" name="order" value="{{ request('order', 'asc') }}">

        <div class="search-group">
            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Buscar..."
                value="{{ request('search') }}"
            >
            <button type="submit" class="search-button">
                Buscar
            </button>
        </div>
    </form>
</div>

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
                    {{-- Abrir em nova aba --}}
                    <a class="icon"
                    title="Abrir em nova aba"
                    href="?path={{ $path }}&open={{ $item['name'] }}"
                    target="_blank"
                    rel="noopener">
                        🔍
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

@extends('layouts.cms')

@section('content')
    <div class="container">
        <h2>Информация о файле</h2>
        <div class="file-info">
            <p>
                <i class="{{ $iconClass }}"></i> {{ basename($file->getName()) }}
            </p>
        </div>
        <a href="{{ env('APP_URL') }}/download/{{$file->getPublic()}}" class="btn btn-primary" download>Скачать файл</a>
    </div>
    {{ env('APP_URL') }}/download/{{$file->getPublic()}}
@endsection

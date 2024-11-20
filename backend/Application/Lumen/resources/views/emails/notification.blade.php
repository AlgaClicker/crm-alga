@extends('layouts.cms')

@section('content')

    <div class="card" style="width: 18rem; су">
        <div class="card-header">
            {{ $notification->getTitle() }}
        </div>
        <div class="card-body">
            Сообщение:
            <p> {{ $notification->getMessage() }} </p>
        </div>
        <div class="card-footer text-muted">
            @foreach ($notification->getFiles() as $file)
                <div> {{ $file->getName() }}</div>
            @endforeach
        </div>
    </div>

@endsection

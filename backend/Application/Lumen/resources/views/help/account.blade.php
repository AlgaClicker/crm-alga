@extends('layouts.cms')

@section('content')


    <div class="card my-4">
        <div class="card-header">

        </div>
        <div class="card-body">

            <table class="table table-sm table-bordered">
                <thead>
                <td>

                </td>
        <td>
            <a href="?sort?email=up">Email</a>
        </td>

        </thead>
        <tbody>
        <!--
        @foreach ($accounts as $account)
            <tr>
                <td>   {{$account->getUsername()}}</td>
                <td>  {{$account->getEmail()}}</td>
            </tr>

                    @endforeach
        -->
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-outline-primary">
                < but >
            </button>

        </div>
    </div>
@endsection

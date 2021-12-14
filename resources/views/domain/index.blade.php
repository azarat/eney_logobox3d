@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8 col-md-5">
                                <h4 class="d-inline">Domains list</h4>
                            </div>
                            <div class="col-sm-4 col-md-7 text-right">
                                <div class="d-inline">
                                    <a href="{{ action('DomainController@create') }}" class="btn btn-primary btn-sm">Add new</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col"> #id </th>
                                <th scope="col"> Domain </th>
                                <th> Key </th>
                                <th> Coefficient </th>
                                <th scope="col" class="text-right">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($domains as $domain)
                                <tr>
                                    <td onclick="goto('{{ action('DomainController@edit', [$domain->id]) }}')">{{ $domain->id }}</td>
                                    <td onclick="goto('{{ action('DomainController@edit', [$domain->id]) }}')">{{ $domain->domain }}</td>
                                    <td onclick="goto('{{ action('DomainController@edit', [$domain->id]) }}')">{{ $domain->key }}</td>
                                    <td onclick="goto('{{ action('DomainController@edit', [$domain->id]) }}')">{{ $domain->coefficient }}</td>
                                    <td class="text-right">
                                        <a href="{{ action('DomainController@edit', [$domain->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <button form="{{ $domain->id }}" onclick="return confirm('are you sure?')" type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        <form id="{{ $domain->id }}" class="form-inline" action="{{ action('DomainController@delete', [$domain->id]) }}" method="POST">
                                            {{ method_field('delete') }}
                                            {{ csrf_field() }}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="float-right">
                            {{ $domains->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function goto(link) {
            window.location = link;
        }
    </script>
@endsection

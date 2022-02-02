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
                                <th scope="col"> Type name </th>
                                <th> Status </th>
                                <th scope="col" class="text-right">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($types as $type)
                                <tr>
                                    <td onclick="goto('{{ action('TypeController@edit', [$type->id]) }}')">{{ $type->id }}</td>
                                    <td onclick="goto('{{ action('TypeController@edit', [$type->id]) }}')">{{ $type->getTranslatedName('ru') }}</td>
                                    <td onclick="goto('{{ action('TypeController@edit', [$type->id]) }}')">
                                        @if($type->status) <i class="fa fa-check text-success"></i> @else <i class="fa fa-times text-danger"></i> @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ action('TypeController@edit', [$type->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        {{--<button form="{{ $type->id }}" onclick="return confirm('are you sure?')" type="submit" class="btn btn-sm btn-outline-danger">Delete</button>--}}
                                        {{--<form id="{{ $type->id }}" class="form-inline" action="{{ action('DomainController@delete', [$type->id]) }}" method="POST">--}}
                                            {{--{{ method_field('delete') }}--}}
                                            {{--{{ csrf_field() }}--}}
                                        {{--</form>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="float-right">
                            {{ $types->links() }}
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

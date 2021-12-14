@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <h4 class="d-inline">Edit -  {{ $applicationType->getTranslatedName('ru') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            <form
                                method="post"
                                enctype="multipart/form-data"
                                action="{{ action('ApplicationTypeController@update', [$applicationType->id]) }}">
                                {{ method_field('PUT') }}
                                @include('application-type.form')
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a class="btn btn-link btn-secondary" href="{{ route('application-type') }}">Cancel</a>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

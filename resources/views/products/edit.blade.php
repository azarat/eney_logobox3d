@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Model: <h2 class="d-inline">{{ $product->model }}</h2>
                    <div class="float-right">
                        <a href="{{ URL::to('/product') . $query }}" class="btn btn-outline-secondary">Back</a>
                        <button form="form-processed" type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" id="form-processed" action="{{ URL::to('/product/save') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="product-id" value="{{ $product->id }}" />
                        <div class="form-group text-right">
                            <input class="custom-checkoxes" type="checkbox" @if($product->processed) checked @endif name="processed" id="processed">
                            <label for="processed">Processed</label>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="main_button_color_hover">2d model Id</label>
                            <input name="model_id_2d" type="text" class="form-control"
                                   id="main_button_color_hover"
                                   value="{{ old('model_id_2d', optional($product)->model_id_2d) }}">
                        </div>
                        <div class="form-group">
                            <label for="main_button_color_hover">3d model Id</label>
                            <input name="model_id_3d" type="text" class="form-control"
                                   id="main_button_color_hover"
                                   value="{{ old('model_id_3d', optional($product)->model_id_3d) }}">
                        </div>

                        @foreach ($areas as $key => $area)
                            <table id="type-{{ $key }}" class="table">
                              <thead>
                                <tr>
                                  <th scope="col">
                                      @foreach ($area['translations'] as $translations)
                                          {{ $translations }}
                                      @endforeach
                                  </th>
                                  <th scope="col" class="text-right">
                                      Enabled
                                      <small>
                                          <span data-id="{{ $key }}" class="checkbox-all js-check">check all</span>
                                          /
                                          <span data-id="{{ $key }}" class="checkbox-all js-uncheck">uncheck all</span>
                                      </small>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>
                                  @foreach ($area['areas'] as $area_id => $area)
                                      <tr class="js-product-row" data-id="{{ $area_id }}">
                                          <td>
                                                  @foreach ($area as $translation)
                                                      {{ $translation }}
                                                  @endforeach
                                          </td>
                                          <td class="text-right">
                                              <input type="checkbox" id="check-{{ $area_id }}" @if(in_array($area_id, $checked)) checked @endif name="area[{{ $area_id }}]" />
                                          </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                            </table>
                        @endforeach
                        <div class="float-right">
                            <span class="checkbox-all js-check-all">check all</span>
                            /
                            <span class="checkbox-all js-uncheck-all">uncheck all</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

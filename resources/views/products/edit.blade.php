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

                        <div class="accordion--div">
                          <div class="accordion__heading--div">
                            <span class="accordion__chevron--div">
                              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-chevron-up fa-w-14 fa-2x"><path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z" class=""></path></svg>
                            </span>
                            Технология / Площадь
                          </div>

                          <div class="accordion__body--div">
                            @foreach ($areas as $key => $area)
                                <table id="type-{{ $key }}" class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col" class="accordion__heading--type">
                                          <span class="accordion__chevron--type">
                                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-chevron-up fa-w-14 fa-2x"><path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z" class=""></path></svg>
                                          </span>
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
                                  <tbody class="accordion__body--type">
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

                          </div>
                        </div>

                        <div class="accordion--div">
                          <div class="accordion__heading--div">
                            <span class="accordion__chevron--div">
                              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-chevron-up fa-w-14 fa-2x"><path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z" class=""></path></svg>
                            </span>
                            Технология / Тип
                          </div>

                          <div class="accordion__body--div">
                            @foreach ($types as $key => $type)
                                <table id="type-{{ $key }}" class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col" class="accordion__heading--type">
                                          <span class="accordion__chevron--type">
                                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-chevron-up fa-w-14 fa-2x"><path fill="currentColor" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z" class=""></path></svg>
                                          </span>
                                          @foreach ($type['translations'] as $translations)
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
                                  <tbody class="accordion__body--type">
                                      @foreach ($type['types'] as $type_id => $type)
                                          <tr class="js-product-row" data-id="{{ $type_id }}">
                                              <td>
                                                      @foreach ($type as $translation)
                                                          {{ $translation }}
                                                      @endforeach
                                              </td>
                                              <td class="text-right">
                                                  <input type="checkbox" id="check-{{ $type_id }}" @if(in_array($type_id, $checked_type)) checked @endif name="type[{{ $type_id }}]" />
                                              </td>
                                          </tr>
                                      @endforeach
                                  </tbody>
                                </table>
                            @endforeach

                          </div>
                        </div>

                        <!-- <div class="float-right">
                            <span class="checkbox-all js-check-all">check all</span>
                            /
                            <span class="checkbox-all js-uncheck-all">uncheck all</span>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

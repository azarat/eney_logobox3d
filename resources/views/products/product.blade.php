@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <h4 class="d-inline">Products</h4>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <select class="form-control js-category">
                                <option value="*">
                                    -- category --
                                </option>
                                @foreach ($available_categories as $cat_id)
                                    <option value="{{ $cat_id['category'] }}"
                                    @if ($category == $cat_id['category'])
                                        selected="selected"
                                    @endif>
                                        {{ $cat_id['category'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-5">
                            <form class="float-right" action="{{ URL::to('product') }}">
                                <input type="text" class="form-control" value="{{ $search }}" placeholder="Search..." name="search" />
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">
                              @if ($order == 'id')
                                  <a href="{{ URL::to('/product') . '?order=id' . '&dir=' . (($dir == 'asc') ? 'desc' : 'asc') }}">
                                      # <i class="fa fa-chevron-{{ ($dir == 'asc') ? 'down' : 'up' }}"></i>
                                  </a>
                              @else
                                  <a href="{{ URL::to('/product') . '?order=id' . '&dir=' . $dir }}">
                                      #
                                  </a>
                              @endif
                          </th>
                          <th scope="col">
                              @if ($order == 'model')
                                  <a href="{{ URL::to('/product') . '?order=model' . '&dir=' . (($dir == 'asc') ? 'desc' : 'asc') }}">
                                      Model <i class="fa fa-chevron-{{ ($dir == 'asc') ? 'down' : 'up' }}"></i>
                                  </a>
                              @else
                                  <a href="{{ URL::to('/product') . '?order=model' . '&dir=' . $dir }}">
                                      Model
                                  </a>
                              @endif
                          </th>
                          <th>
                              Category
                          </th>
                          <th class="text-center" scope="col">
                              @if ($order == 'processed')
                                  <a href="{{ URL::to('/product') . '?order=processed' . '&dir=' . (($dir == 'asc') ? 'desc' : 'asc') }}">
                                      Processed <i class="fa fa-chevron-{{ ($dir == 'asc') ? 'down' : 'up' }}"></i>
                                  </a>
                              @else
                                  <a href="{{ URL::to('/product') . '?order=processed' . '&dir=' . $dir }}">
                                      Processed
                                  </a>
                              @endif
                          </th>
                          <th scope="col" class="text-right">Edit</th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach ($products as $product)
                              <tr>
                                  <td onclick="goto('{{ action('ProductController@edit', [$product->id]) }}')">{{ $product->id }}</td>
                                  <td onclick="goto('{{ action('ProductController@edit', [$product->id]) }}')">{{ $product->model }}</td>
                                  <td onclick="goto('{{ action('ProductController@edit', [$product->id]) }}')">{{ $product->category }}</td>
                                  <td onclick="goto('{{ action('ProductController@edit', [$product->id]) }}')" class="text-center">
                                      @if($product->processed) <i class="fa fa-check text-success"></i> @else <i class="fa fa-times text-danger"></i> @endif
                                  </td>
                                  <td class="text-right">
                                      <a href="{{ URL::to('/product/edit/' . $product->id) . $query }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                    </table>
                    <div class="float-right">
                        {{ $pagination->appends(Request::all())->links() }}
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

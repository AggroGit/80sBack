@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">{{$tabletate['name'] ?? ''}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            {{$tabletate['name'] ?? ''}}
            @if($tabletate['options']['add']?? false)
              <div class="d-none d-md-inline-block  ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <a href="{{url('admin/'.$tabletate['singular'].'/add')}}">
                  <button type="button" class="btn btn-success btn-sm">
                    Añadir
                  </button>
                </a>
              </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                          @if($tabletate['options']['image']?? false)
                            <th> Imagen </th>
                          @endif
                          @foreach($tabletate['headers'] as $key => $table)
                            <th>{{$key}}</th>
                          @endforeach
                          @if($tabletate['options']['edit']?? false or $tabletate['options']['remove']?? false)
                            <th>Opciones</th>
                          @endif
                        </tr>
                    </thead>
                    <tfoot>
                        @if($tabletate['options']['image']?? false)
                          <th><!-- Imagen --></th>
                        @endif
                        @foreach($tabletate['headers'] as $key => $table)
                          <th>{{$key}}</th>
                        @endforeach
                        @if($tabletate['options']['edit']?? false or $tabletate['options']['remove']?? false)
                          <th>Opciones</th>
                        @endif
                    </tfoot>
                    <tbody>
                      @foreach($tabletate['data'] as $key => $data)
                      <tr>
                          @if($tabletate['options']['image']?? false)
                            <td class="text-center">
                              <img src="{{$data['image']['sizes']['VerySmall']?? $data['images'][0]['sizes']['VerySmall']?? ''}}" alt="">
                            </td>
                          @endif
                          <!-- CAMPOS -->
                          @foreach($tabletate['headers'] as $keyOption => $header)
                            <td>
                              <!-- RELACIONES A 1-->
                              @if(isset($header['model_name']))

                                @if(!$header['multiple']?? false)
                                  @if($header['url']?? false)
                                    <a href="{{url($header['url'])}}/{{$data[$header['model_name']]['id']?? ""}}">
                                      {{$data[$header['model_name']][$header['show']]?? ""}}
                                    </a>
                                  @else
                                    {{$data[$header['model_name']][$header['show']]?? ""}}
                                  @endif
                                @else

                                <!-- RELACIONES A MUCHOS-->
                                @foreach($data->{$header['model_name'].'s'} as $opt)
                                  {{$opt[$header['show']]}} ,
                                @endforeach



                                @endif
                                <!-- !RELACIONES -->

                                <!-- CAMPOS -->
                                @else
                                  {{$data[$header]}}
                                @endif

                            </td>
                          @endforeach
                          <!-- OPCIONES -->
                          @if($tabletate['options']['edit']?? false or $tabletate['options']['remove']?? false)
                          <td>
                            @if($tabletate['options']['edit']?? false)
                              <a href="{{url('admin/'.$tabletate['singular'].'/edit/'.$data['id'])}}">
                                <button type="button" class="btn btn-success btn-sm">
                                  Editar
                                </button>
                              </a>
                            @endif

                            @if($tabletate['options']['remove']?? false)
                              <a href="{{url('admin/'.$tabletate['singular'].'/remove/'.$data['id'])}}">
                                <button type="button" class="btn btn-danger btn-sm">
                                  Eliminar
                                </button>
                              </a>
                            @endif
                          </td>
                          @endif
                      </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

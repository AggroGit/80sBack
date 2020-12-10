@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">{{$tabletate['name'] ?? ''}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Añadir {{$tabletate['name'] ?? ''}}
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="{{url('admin/'.$tabletate['singular'].'/add')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$tabletate['data']['id']?? false}}">
                  <div class="form-row">
                    @foreach($tabletate['headers'] as $key => $header)
                      <div class="col-md-6">
                          <div class="form-group">
                            <!-- SI ES UN CAMPO DE TEXTO  -->
                            @if(!is_array($header)?? false)
                              @if(Schema::hasColumn($model->getTable(), $header))
                                @if($header !== "expires_at")
                                <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                                <input class="form-control py-4" id="inputLastName" type="text" value="{{$tabletate['data'][$header]?? ''}}" name="{{$header}}" placeholder="{{$key}}" />
                                @else
                                <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                                <input class="form-control py-4" id="inputLastName" type="date" value="{{$tabletate['data'][$header]?? ''}}" name="{{$header}}" placeholder="{{$key}}" />
                                @endif
                              @endif
                            @else
                            <!-- Si no es un campo de texto -->
                            <label class="small mb-1" for="{{$key}}">{{$key}}</label>
                            <!-- MULTISELECT -->
                            @if($header['multiple']?? false)
                            <select class=" form-control multiselect selectpicker" name="{{$header['model_name']}}[]" multiple data-live-search="true">
                              @foreach($header['select'] as $select)
                                <option
                                @if ($model->{$header['model_name'].'s'}->find($select->id))
                                  selected
                                @endif
                                 value="{{$select['id']}}">{{$select[$header['show']]}}
                               </option>
                              @endforeach
                            </select>
                            @else
                            <!-- SELECT UNICO -->
                            <select class="form-control" name="{{$header['model_name'].'_id'}}" id="sel1">
                                <option></option>
                                @foreach($header['select'] as $select)
                                  <option
                                  @if ($tabletate['data']?? false and $tabletate['data'][$header['model_name'].'_id']==$select['id'])
                                    selected
                                  @endif
                                   value="{{$select['id']}}">{{$select[$header['show']]}}
                                 </option>
                                @endforeach
                            </select>
                            @endif


                            @endif
                          </div>
                      </div>
                    @endforeach


                    @if($tabletate['options']['image']?? false)
                    @if(Schema::hasColumn($model->getTable(), 'image_id'))
                        <div class="col-md-4">
                            <div class="form-group">
                              <label class="small mb-1" for="image">Imagen</label>
                              <picture-input
                                name="image"
                                ref="image"
                                width="200"
                                height="200"
                                margin="16"
                                accept="image/jpeg,image/png"
                                size="10"
                                @if($tabletate['data']['image']?? $tabletate['data']['images'][0]?? false)
                                prefill="{{$tabletate['data']['image']['sizes']['Big']?? $tabletate['data']['images'][0]['sizes']['Big']?? $tabletate['data']['images'][0]?? ''}}"
                                @endif
                                buttonClass="btn"
                                >
                              </picture-input>
                            </div>
                        </div>
                      @else
                      <div class="col-md-12">
                          <div class="form-group">
                            @if($model->images->count()>1)
                            <h4>Imágenes</h4>

                            <br>
                            <br>
                            @endif
                            <div class="row down-2">
                              @foreach($model->images as $image)
                              <div class="col-md-4 down-2">
                                <br>
                                <br>
                                <picture-input
                                  name="image_{{$image->id}}"
                                  ref="image"
                                  width="200"
                                  height="200"
                                  margin="16"
                                  accept="image/jpeg,image/png,image/jpg"
                                  size="10"
                                  customStrings="{
                                    change: 'Cambiar imágen'
                                  }"
                                  prefill="{{$image['sizes']['Big']?? ''}}"
                                  buttonClass="btn"
                                  >
                                </picture-input>
                                <div class="text-center">
                                  <a class="text-danger text-center" href="{{url('/admin/image/remove/'.$image->id)}}">
                                      Eliminar imágen
                                  </a>
                                </div>

                              </div>
                              @endforeach
                              <div class="col-md-3">
                                <br>
                                <br>

                                <h4>Añadir Imágenes</h4>
                                <input type="file" id="files" accept="image/jpeg,image/png,image/jpg" name="images[]" multiple><br><br>

                              </div>

                            </div>
                          </div>
                      </div>
                      @endif
                    @endif

                  </div>
                  <div class="d-none d-md-inline-block  ml-auto mr-0 mr-md-3 my-2 my-md-0">
                      <button type="submit" class="btn btn-success btn-sm">
                        Actualizar
                      </button>
                  </div>
              </form>
          </div>
        </div>
    </div>
</div>
@endsection

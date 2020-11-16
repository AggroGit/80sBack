@extends('perpetua.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">{{$tabletate['name'] ?? ''}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Añadir Notícia
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="{{url('/perpetua/admin/news/add')}}" enctype="multipart/form-data">
                @csrf

                @if(isset($data)?? false)
                <input type="hidden" name="id" value="{{$data->id}}">
                @endif
                  <div class="form-row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label class="small mb-1" for="title">Título</label>
                            <input class="form-control py-4" id="inputLastName" type="text" value="@if(isset($data)) {{$data->title}} @endif" name="title" placeholder="" />
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                            <label class="small mb-1" for="subtitle">Subtítulo</label>
                            <input class="form-control py-4" id="inputLastName" type="text" value="@if(isset($data)) {{$data->subtitle}} @endif" name="subtitle" placeholder="" />
                          </div>
                      </div>





                      <div class="col-md-12">
                          <div class="form-group">
                            <label class="small mb-1" for="subtitle">Notícia</label>
                            <textarea class="form-control py-4" name="text"  rows="8" cols="80">@if(isset($data)) {{$data->text}} @endif</textarea>
                            <!-- <input  id="inputLastName" type="text" value="asd" name="text" placeholder="" /> -->
                          </div>
                      </div>

                      <div class="col-md-6 ">
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
                              @if(isset($data->image))
                                prefill="{{$data->image['sizes']['Big']}}"
                              @endif

                              buttonClass="btn"
                              >
                            </picture-input>
                          </div>
                      </div>

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

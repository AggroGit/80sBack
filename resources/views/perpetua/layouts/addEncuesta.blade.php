@extends('perpetua.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Encuestas</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Encuestas
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="{{url('/perpetua/admin/encuestas/add')}}" enctype="multipart/form-data">
                @csrf

                @if(isset($data)?? false)
                <input type="hidden" name="id" value="{{$data->id}}">
                @endif
                  <div class="form-row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label class="small mb-1" for="title">Url de encuesta de negocio</label>
                            <input class="form-control py-4" id="url_business" type="text" value="@if(isset($data)) {{$data->url_business}} @endif" name="url_business" placeholder="" />
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                            <label class="small mb-1" for="subtitle">Url de encuesta de cliente</label>
                            <input class="form-control py-4" id="url_clients" type="text" value="@if(isset($data)) {{$data->url_clients}} @endif" name="url_clients" placeholder="" />
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

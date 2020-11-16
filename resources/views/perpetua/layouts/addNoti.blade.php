@extends('perpetua.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">{{$tabletate['name'] ?? ''}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Añadir Notificación
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="{{url('/perpetua/admin/notifications/add')}}" enctype="multipart/form-data">
                @csrf

                @if(isset($data)?? false)
                <input type="hidden" name="id" value="{{$data->id}}">
                @endif
                  <div class="form-row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label for="sel1">Selecciona tipo de notificación:</label>
                            <select name="type" class="form-control" id="sel1">
                              <option value="push">Push</option>
                              <option value="email">Correo</option>
                              <option value="all">Correo y Push</option>
                            </select>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                            <label for="sel1">Selecciona tipo de usuarios:</label>
                            <select name="for" class="form-control" id="sel1">
                              <option value="business">Negocios</option>
                              <option value="client">Clientes</option>
                              <option value="all">Negocios y clientes</option>
                            </select>
                          </div>
                      </div>

                      <div class="col-md-12">
                          <div class="form-group">
                            <label class="small mb-1" for="subtitle">Título</label>
                            <input class="form-control py-4" id="inputLastName" type="text" value="@if(isset($data)) {{$data->title}} @endif" name="title" placeholder="" />
                          </div>
                      </div>


                      <div class="col-md-12">
                          <div class="form-group">
                            <label class="small mb-1" for="subtitle">Fecha de envío</label>
                            <datee name="send_at" value="@if(isset($data)) {{$data->send_at}} @endif"></datee>
                      </div>

                      <div class="col-md-12">
                          <div class="form-group">
                            <label class="small mb-1" for="subtitle">Cuerpo</label>
                            <textarea class="form-control py-4" name="message"  rows="8" cols="80">@if(isset($data)) {{$data->message}} @endif</textarea>
                            <!-- <input  id="inputLastName" type="text" value="asd" name="text" placeholder="" /> -->
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

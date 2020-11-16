@extends('perpetua.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Añadir Notificacion</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Añadir Notificacion
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-md-3">
                      <label for="title">Para: </label>
                      <div class="checkbox">
                        <label><input type="checkbox" value=""> Comercios</label>
                      </div>
                      <div class="checkbox">
                        <label><input type="checkbox" value=""> Clientes</label>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <label for="title">Tipo: </label>
                      <div class="checkbox">
                        <label><input type="checkbox" value=""> Email</label>
                      </div>
                      <div class="checkbox">
                        <label><input type="checkbox" value=""> Push (App)</label>
                      </div>
                  </div>
                </div>
                <div class="forn-group">
                  <label for="title">Fecha</label>
                  <datee></datee>
                </div>

                <div class="form-group">
                  <label for="title">Título</label>
                  <input type="text" class="form-control" autocomplete="off" name="title" id="title">
                </div>
                <div class="form-group">
                  <label for="message">Mensaje</label>
                  <textarea class="form-control" rows="5" autocomplete="off" id="message" name="message"></textarea>
                </div>


                <button type="submit" class="btn btn-success">Enviar</button>
              </form>
          </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center down-screen ">
        <div class="col-md-12 text-center aparecerArriba">
          <img style="
          max-width: 200px;
          width: 100%;
          margin: auto;
          " src="{{asset('/logos/merco.png')}}" alt="">
        </div>
        <br>
        <div class="row down">
          <div class="col-md-12 text-center">
            <h1>Merco</h1>
          </div>
        </div>



    </div>

    <div class="row justify-content-center down-screen">
      <div class="container text-center">
        <a href="https://web.merco.app/" >
          <button type="button" class="btn btn-primary aparecer">Ir a nuestra Web</button>
        </a>

      </div>
    </div>
    @if(isset($correct))
    <div class="row justify-content-center">
      <div class="col-md-7">
        <div class="alert alert-success">
          <strong>¡Exito!</strong> {{$correct}}
        </div>
      </div>
    </div>
    @endif
</div>
@endsection

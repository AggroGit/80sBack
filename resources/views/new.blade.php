@extends('layouts.app')

@section('cssExtra')
<link href="{{ asset('css/news.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="contieneImagenNews">
          <img src="{{$new->image->sizes['Med']}}" alt="">
        </div>
      </div>
    </div>


    <div class="row justify-content-center">
      <div class="col-md-12 down">
        <p>{{Carbon\Carbon::parse($new->publishAt)}}</p>
      </div>
      <div class="col-md-12 titulo">
        <h1>{{$new->title}}</h1>
      </div>

      <div class="col-md-12 down">
        {{$new->text}}
      </div>
    </div>

</div>
@endsection

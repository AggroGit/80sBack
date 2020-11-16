@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row datos">
       <div class="col-xl-3 col-md-6">
           <div class="card infoCard text-white mb-4">
               <div class="card-body">Número de Productos: {{$numProducts}}</div>

           </div>
       </div>
       <div class="col-xl-3 col-md-6">
           <div class="card infoCard text-white mb-4">
               <div class="card-body">Número de negocios: {{$numBusiness}}</div>

           </div>
       </div>
       <div class="col-xl-3 col-md-6">
           <div class="card infoCard  text-white mb-4">
               <div class="card-body">Número de Usuarios {{$numUsers}}</div>

           </div>
       </div>
       <div class="col-xl-3 col-md-6">
           <div class="card infoCard text-white mb-4">
               <div class="card-body">Número de pedidos {{$numOrders}}</div>

           </div>
       </div>
       <div class="col-xl-3 col-md-6">
           <div class="card infoCard text-white mb-4">
               <div class="card-body">Número de Imágenes {{$numImages}}</div>

           </div>
       </div>


     </div>
</div>
@endsection

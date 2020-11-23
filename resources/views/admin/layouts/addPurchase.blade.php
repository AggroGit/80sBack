@extends('admin.layouts.origin')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Pedido 0000{{$purchase->id}}</h1>
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Pedido 0000{{$purchase->id}}
        </div>
        <div class="card-body">
          <div class="card-body">
              <form method="POST" action="" enctype="multipart/form-data">
                <div class="container">
                  <div class="col-md-12">
                    
                  </div>
                </div>
              </form>
          </div>
        </div>
    </div>
</div>
@endsection

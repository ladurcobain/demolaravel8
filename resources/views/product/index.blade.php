@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Products Management</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('product.create') }}"> Create New product</a>
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif


<table class="table table-bordered">
 <tr>
   <th width="10%">Code</th>	
   <th>Name</th>
   <th width="5%">Stock</th>
   <th width="5%">Image</th>
   <th width="20%">Action</th>
 </tr>
 @foreach ($data as $key => $product)
  <tr>
    <td>{{ $product->product_code }}</td>
    <td>{{ $product->product_name }}</td>
    <td>{{ $product->stock }}</td>  
    <td class="text-center">
         <img src="{{ Storage::url('public/products/').$product->product_image }}" class="rounded" style="width: 36px">
    </td>
	<td class="text-center">
       <a class="btn btn-info" href="{{ route('product.show',$product->id) }}">Show</a>
       <a class="btn btn-primary" href="{{ route('product.edit',$product->id) }}">Edit</a>
        {!! Form::open(['method' => 'DELETE','route' => ['product.destroy', $product->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("Apakah Anda Yakin ?");']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>


{!! $data->render() !!}


<p class="text-center text-primary"><small>Soal Test Coding untuk Programmer – PT. Tamaris Hidro</small></p>
@endsection
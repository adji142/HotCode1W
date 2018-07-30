@extends('layouts.default')

@section('breadcrumb')
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{ route('tokodraft.index') }}">Toko Draft</a></li>
<li class="breadcrumb-item active">View KTP</li>
@endsection

@section('main_container')
<div class="mainmain">
  <div class="row">
    <div class="x_panel">
      <div class="x_title">
        <h2>Nama Toko : {{ $tokodraft ? $tokodraft->NamaToko : '' }}</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li style="float: right;">
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <div class="col-md-7 col-sm-7 col-xs-12">
          <div class="product-image">
          	@if(empty($tokodraft->foto))
          	<img src="{{ url('assets/img/no-image.jpg') }}" alt="..." />
          	@else
            <img src="{{ $tokodraft ? $tokodraft->foto : ''}}" alt="..." />
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
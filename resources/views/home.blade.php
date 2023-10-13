@extends('layouts.admin')
@section('header', 'Home')
@section('content')

<div class="row">
    <!-- Box 1: Total Books -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$total_product}}</h3>
                <p>Total Product</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{url('products')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Box 2: Total Members -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{$total_member}}</h3>
                <p>Total Members</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
            <a href="{{url('members')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Box 3: Total Publishers -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{$total_sale}}</h3>
                <p>Total Sale</p>
            </div>
            <div class="icon">
                <i class="fas fa-upload"></i>
            </div>
            <a href="{{url('sale')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Box 4: Total Transactions -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$total_purchase}}</h3>
                <p>Total Purchase</p>
            </div>
            <div class="icon">
                <i class="fas fa-download"></i>
            </div>
            <a href="{{url('purchases')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endsection

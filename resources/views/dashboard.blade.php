@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">

                <div class="small-box bg-info center-vertical h-100">
                    <div class="inner">
                    <p>
                    <a href="{{route('admin.maintenance-requests.create')}}" class="text-white">create Maintenance Request <i class="fas fa-cog"></i></a>
                    </p>
                       
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                   
                </div>
            </div>

           

        </div>
    </div>
</section>
@stop
@section('css')
<style>
.center-vertical{
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>

@stop
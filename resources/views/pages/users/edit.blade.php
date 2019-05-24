@extends('layout.app')

@section('title', isset($is_profile)? ' | Edit My Profile' : ' | Edit user')

@section('content')


    <section class="content-header">
        <h1>
            @if(isset($is_profile)) Edit My profile @else Edit user #{{ $user->id }} @endif
        </h1>

        @if(!isset($is_profile))
            <ol class="breadcrumb">
                <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ url('/admin/users') }}"> Users </a></li>
                <li class="active">Edit</li>
            </ol>
        @endif
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        @if(!isset($is_profile))
                            <a href="{{ url('/admin/users') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>

                            <br />
                            <br />
                        @endif

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/admin/users/' . $user->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('pages.users.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

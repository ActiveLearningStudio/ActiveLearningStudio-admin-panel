@extends('adminlte::page')

@section('title', 'Add User')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Add User</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Create User Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->route('admin.users.store')->class('form-horizontal')->post()
                    ->rules([
                        'password' => 'required|min:8',
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'organization_name' => 'required',
                        'job_title' => 'required',
                        'email' => 'required|email',
                        ])
                    }}
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('first_name', 'First Name')->id('first_name')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('last_name', 'Last Name')->id('last_name')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('organization_name', 'Organization Name')->id('organization_name')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('job_title', 'Job Title')->id('job_title')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::email('email', 'Email')->id('email')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::password('password', 'Password')->id('password')->addClass('form-control')->required() }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Create User')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')}}
                </div>
                <!-- /.card-footer -->
                {{ Aire::close() }}
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        $(".cancel").on("click", function (e) {
            e.preventDefault();
        });
    </script>
@endsection

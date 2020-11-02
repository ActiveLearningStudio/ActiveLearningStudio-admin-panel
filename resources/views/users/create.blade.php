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
                {{ Aire::open()->class('form-horizontal')->post()->id('user-form')
                    ->rules([
                        'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/',
                        'first_name' => 'required|max:255',
                        'last_name' => 'required|max:255',
                        'organization_name' => 'required|string|max:50',
                        'job_title' => 'required|string|max:255',
                        'organization_type' => 'required|string|max:255',
                        'email' => 'required|email|max:255',
                        ])
                        ->messages([
                         'regex' => ':attribute must be 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase and 1 Numeric character.',
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
                            {{ Aire::select([], 'organization_type', 'Organization Type')->id('organization_type')->addClass('form-control')->required() }}
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
                            {{ Aire::input('password', 'Password')->id('password')->addClass('form-control')->required() }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Create User')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.users.index'))}}
                </div>
                <!-- /.card-footer -->
                {{ Aire::close() }}
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        // form submit
        let url = api_url + api_v + "/admin/users";
        serializedSubmitForm("#user-form", url);

        // organization types
        callParams.Url = api_url + api_v + "/organization-types";
        ajaxCall(callParams, {}, function (result){
            result = result.data;
            let organizationTypes = [];
            $.map(result, function (item) {
                organizationTypes.push({id: item.label, text: item.label});
            });
            $('#organization_type').select2({
                theme: 'bootstrap4',
                data: organizationTypes,
            });
        });
    </script>
@endsection

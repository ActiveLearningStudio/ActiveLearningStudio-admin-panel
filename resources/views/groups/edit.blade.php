@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit User</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-7">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Update Info</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->class('form-horizontal')->id('user_update')->put()->bind($response['data'])
                      ->rules([
                        'first_name' => 'required|max:255',
                        'last_name' => 'required|max:255',
                        'password' => 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/',
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
                            {{ Aire::email('email', 'Email')->id('email')->addClass('form-control')->required() }}
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
                            {{ Aire::input('password', 'Password')->id('password')->addClass('form-control')->placeholder('Leave Blank for unchanged.') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'clone_project_id', 'Clone Project')->addClass('form-control')->id('clone_project') }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Update User')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.users.index'))}}
                </div>
                <!-- /.card-footer -->
                {{ Aire::close() }}
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Projects</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>Status</th>
                            <th>Project Name</th>
                            <th>Indexing</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($response['data']['projects'] as $project)
                            <tr>
                                <td>{{$project['status_text']}}</td>
                                <td><a class="modal-preview" data-target="#preview-project" href="javascript:void(0)"
                                       data-href="{{route('admin.users.project-preview.modal', $project['id'])}}">
                                        {{'ID: '.$project['id']. ' | ' . $project['name']}}</a>
                                </td>
                                <td>{{$project['indexing_text']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    @include('layouts.base-modal', ['modal' => ['id' => 'preview-project', 'class' => 'modal-xl', 'title' => 'Preview Project']])
@stop
@section('js')
    <script type="text/javascript">
        // initialize select2 for clone project field
        $("#clone_project").select2({
            theme: 'bootstrap4',
            // allowClear: true,  currently not working - need to debug
            minimumInputLength: 0,
            ajax: {
                url: api_url + api_v + "/admin/projects",
                dataType: 'json',
                type: "GET",
                delay: 700,
                data: function (params) {
                    // Query parameters will be ?search=[term]&type=public&limit=100
                    return {
                        q: params.term,
                        type: 'public',
                        users: true,
                        page: params.page || 1,
                        exclude_starter: 1
                    };
                },
                processResults: function (data) {
                    var projects = data.data;
                    return {
                        results: $.map(projects, function (item) {
                            var emails = "";
                            (item.users).forEach(function (user) {
                                emails = emails ? emails + ", " + user.email : user.email;
                            });
                            return {
                                text: item.name + " - ( " + emails + ")",
                                id: item.id,
                            }

                        }),
                        pagination: {
                            more: data.links.next
                        }
                    };
                }
            }
        });

        // form submit event prevent
        $("#user_update").on('submit', function (e) {
            e.preventDefault();
            resetAjaxParams("POST");
            let pass_sel = $("#password");

            // if empty value
            if (!pass_sel.val()) {
                pass_sel.attr('disabled', true);
            }

            callParams.Url = api_url + api_v + "/admin/users/" + '{{$response['data']['id']}}';
            // Set Data parameters
            dataParams = $(this).serialize();
            ajaxCall(callParams, dataParams, function (result) {
                if ($("#clone_project").val()) {
                    location.reload();
                }
            });
            pass_sel.removeAttr('disabled');
        });

        // organization types
        callParams.Url = api_url + api_v + "/organization-types";
        ajaxCall(callParams, {}, function (result){
            result = result.data;
            let organizationTypes = [];
            $.map(result, function (item) {
                organizationTypes.push({id: item.label, text: item.label});
            });
            let orgSelector = $('#organization_type');
            orgSelector.select2({
                theme: 'bootstrap4',
                data: organizationTypes,
            });
            orgSelector.val('{{$response['data']['organization_type']}}').trigger('change');;
        });
    </script>
@endsection

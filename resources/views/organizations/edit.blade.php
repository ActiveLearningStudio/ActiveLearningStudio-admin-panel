@extends('adminlte::page')

@section('title', 'Edit Organization')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Organization</h1>
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
                {{ Aire::open()->class('form-horizontal')->id('organization_update')->put()->bind($response['data'])
                      ->rules([
                        'name' => 'required|max:255',
                        'description' => 'required|max:255',
                        'parent_id' => 'max:255',
                        'image' => 'required',
                        'domain' => 'required|max:255',
                        ])
                  }}
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('name', 'Name')->id('name')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('description', 'Description')->id('description')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::input('domain', 'Domain')->id('domain')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::file('image', 'Image')->id('image') }}
                            <p></p>
                            <img id="image-preview" src="{{validate_api_url($response['data']['image'])}}"
                                 alt="Uploaded Image" onerror="this.style.display='none'"
                                 style="max-width: 150px"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'parent_id', 'Parent')->addClass('form-control')->id('parent_id') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'clone_project_id', 'Clone Project')->addClass('form-control')->id('clone_project') }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'member_id', 'Add Member')->addClass('form-control')->id('member_id') }}
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    {{Aire::submit('Update Organization')->addClass('btn btn-info')}}
                    {{Aire::submit('Cancel')->addClass('btn btn-default float-right cancel')->data('redirect', route('admin.organizations.index'))}}
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Users</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($response['data']['users'] as $user)
                            <tr>
                                <td>{{$user['email']}}</td>
                                <td>{{$user['organization_role']}}</td>
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
        // form submit
        let url = api_url + api_v + "/admin/organizations/" + {{$response['data']['id']}} + "/parent-options";
        initializeSelect2("#parent_id", url, ["name"]);

        @if (isset($response['data']['parent']))        
            // set the already selected user option
            var $option = $("<option selected></option>").val('{{$response['data']['parent']['id']}}').text(decodeHTML('{{$response['data']['parent']['name']}}'));
            $('#parent_id').append($option).trigger('change');
        @endif

        url = api_url + api_v + "/admin/organizations/" + {{$response['data']['id']}} + "/member-options";
        initializeSelect2("#member_id", url, ["email"]);

        // form submit
        url = api_url + api_v + "/admin/organizations/" + {{$response['data']['id']}};
        multiPartFormSubmission("#organization_update", url, function (result) {
            if ($("#clone_project").val() || $("#member_id").val()) {
                location.reload();
            }
        });

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
                        organizations: true,
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
    </script>
@endsection

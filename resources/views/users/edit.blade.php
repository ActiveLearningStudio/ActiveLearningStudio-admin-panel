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
                        'organization_name' => 'max:255',
                        'job_title' => 'max:255',
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
                    @empty(! $response['data']['projects'])
                        <a class="float-right btn-xs btn-primary" onclick="updateIndexes(this)"
                           href="javascript:void(0)">Update
                            Elastic</a>
                    @endempty
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <p class="ml-2 mb-0"><small><i>Public checkbox will toggle the project public status ASAP as
                                checked/un-checked.</i></small></p>
                    <p class="ml-2 mb-0"><small><i>Elastic Search can be enabled/disabled for projects by clicking on
                                EASTIC UPDATE button.</i></small></p>
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"> Elastic Search</th>
                            <th>Project Name</th>
                            <th>Public</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($response['data']['projects'] as $project)
                            <tr>
                                <td>
                                    <input type="checkbox" class="project_id"
                                           {{$project['elasticsearch'] ? 'checked' : ''}} value="{{$project['id']}}">
                                    <span class="elastic_search">{{$project['elasticsearch'] ? ' Yes': ' No'}}</span>
                                </td>
                                {{-- <td><a href="{{config('app.frontend_url')}}project/{{$project['id']}}/shared"
                                        target="_blank">{{$project['name']}}</a></td>   --}}
                                <td><a class="modal-preview" data-target="#preview-project" href="javascript:void(0)"
                                       data-href="{{route('admin.users.project-preview.modal', $project['id'])}}">{{$project['name']}}</a>
                                </td>
                                <td>
                                    <input type="checkbox" class="project_public"
                                           onclick="togglePublic(this, {{$project['id']}})"
                                           {{$project['is_public'] ? 'checked' : ''}} value="{{$project['id']}}">
                                    <span class="is_public">{{$project['is_public'] ? ' Yes': ' No'}}</span>
                                </td>
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
                        page: params.page || 1
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

        // function for updating the indexes of the project
        function updateIndexes(ele) {
            var index_projects = [];
            var remove_index_projects = [];
            $.each($(".project_id"), function () {
                if ($(this).prop("checked")) {
                    index_projects.push($(this).val());
                } else {
                    remove_index_projects.push($(this).val());
                }
            });

            resetAjaxParams("POST");
            callParams.Url = api_url + api_v + "/admin/projects/indexes";
            // Set Data parameters
            dataParams.index_projects = index_projects;
            dataParams.remove_index_projects = remove_index_projects;
            ajaxCall(callParams, dataParams, function (result) {
                $(".project_id").next('.elastic_search').text('No');
                $(".project_id:checked").next('.elastic_search').text('Yes');
            });
        }

        // check and uncheck all
        $("#check_all").change(function () {
            $(".project_id:checkbox").prop('checked', $(this).prop("checked"));
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

        // toggle elastic search status for single project
        function updateIndex(ele, id) {
            resetAjaxParams();
            callParams.Url = api_url + api_v + "/admin/projects/" + id + "/index";
            ajaxCall(callParams, dataParams, function (result) {
                $(ele).toggleText('Index', 'Remove Index'); // toggle the button text
                let projectCheckBox = $(".project_id[value=" + id + "]");
                projectCheckBox.prop('checked', !projectCheckBox.prop("checked")); // check uncheck the relevant checkbox
                projectCheckBox.next('.elastic_search').toggleText('Yes', 'No'); // Yes, No
            });
        }

        // update the is_public status
        function togglePublic(ele, id) {
            resetAjaxParams();
            callParams.Url = api_url + api_v + "/admin/projects/" + id + "/public-status";
            ajaxCall(callParams, dataParams, function (result) {
                $(ele).next('.is_public').toggleText('Yes', 'No'); // toggle the button text
            });
        }
    </script>
@endsection

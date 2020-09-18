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
        <div class="col-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Update Info</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ Aire::open()->class('form-horizontal')->id('user_update')->put()->bind($response['data'])
                      ->rules([
                        'first_name' => 'required',
                        'last_name' => 'required',
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
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Projects</h3>
                    @empty(! $response['data']['projects'])
                        <a class="float-right btn-xs btn-primary" onclick="updateIndexes(this)" href="javascript:void(0)">Update
                            Index</a>
                    @endempty
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="check_all"></th>
                            <th>Project Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($response['data']['projects'] as $project)
                            <tr>
                                <td>
                                    <input type="checkbox" class="project_id"
                                           {{$project['elasticsearch'] ? 'checked' : ''}} value="{{$project['id']}}">
                                </td>
                                {{-- <td><a href="{{config('app.frontend_url')}}project/{{$project['id']}}/shared"
                                        target="_blank">{{$project['name']}}</a></td>   --}}
                                <td><a class="modal-preview" data-target="#preview-project" href="javascript:void(0)"
                                       data-href="{{route('admin.users.project-preview.modal', $project['id'])}}">{{$project['name']}}</a>
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
        // redirect to users listing page on cancel
        $(".cancel").on("click", function (e) {
            e.preventDefault();
            window.location.href = $(this).data('redirect');
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
                delay: 500,
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
            var projects = [];
            $.each($(".project_id:checked"), function () {
                projects.push($(this).val());
            });

            success_sel.find('.alert-success').remove();
            resetAjaxParams("POST");
            callParams.Url = api_url + api_v + "/admin/projects/indexes/" + '{{$response['data']['id']}}';
            // Set Data parameters
            dataParams.projects = projects;
            ajaxCall(callParams, dataParams, function (result) {
                if (result.message) {
                    showMessage(result.message);
                }
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
            success_sel.find('.alert-success').remove();

            // if empty value
            if (!pass_sel.val()) {
                pass_sel.attr('disabled', true);
            }

            callParams.Url = api_url + api_v + "/admin/users/" + '{{$response['data']['id']}}';
            // Set Data parameters
            dataParams = $(this).serialize();
            ajaxCall(callParams, dataParams, function (result) {
                if (result.message) {
                    showMessage(result.message);
                }
                if ($("#clone_project").val()) {
                    location.reload();
                }
            });
            pass_sel.removeAttr('disabled');
        });

        // load preview modal data dynamically
        $(".modal-preview").on("click", function (e) {
            e.preventDefault();
            resetAjaxParams();
            let target = $(this).data('target');
            callParams.Url = $(this).data('href');
            ajaxCall(callParams, dataParams, function (result) {
                $(target).modal('show');
                if (!result.html) {
                    $(target).find('.modal-body').html('Data not found!');
                    return false;
                }
                $(target).find('.modal-body').html(result.html);
            });
        });

        // toggle elastic search status for single project
        function updateIndex(ele, id) {
            resetAjaxParams();
            callParams.Url = api_url + api_v + "/admin/projects/" + id + "/index";
            ajaxCall(callParams, dataParams, function (result) {
                $(ele).toggleText('Index', 'Remove Index'); // toggle the button text
                let projectCheckBox =  $(".project_id[value=" + id + "]");
                projectCheckBox.prop('checked',  !projectCheckBox.prop("checked")); // check uncheck the relevant checkbox
                if (result.message) {
                    showMessage(result.message);
                }
            });
        }
    </script>
@endsection

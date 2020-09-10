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
                {{ Aire::open()->route('admin.users.store')->class('form-horizontal')->post()->bind($response['data'])
                      ->rules([
                        'password' => 'required|min:8',
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
                            {{ Aire::password('password', 'Password')->id('password')->addClass('form-control')->required() }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            {{ Aire::select([], 'cloneprojectid', 'Clone Project')->addClass('form-control')->id('clone_project') }}
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
                    <a class="float-right btn-xs btn-primary" onclick="updateIndex(this)" href="javascript:void(0)">Update Index</a>
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
                                <td><input type="checkbox" class="project_id" value="{{$project['id']}}"></td>
                                <td><a href="{{config('app.frontend_url')}}/project/preview2/{{$project['id']}}"
                                       target="_blank">{{$project['name']}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        // redirect to users listing page on cancel
        $(".cancel").on("click", function (e) {
            e.preventDefault();
            window.location.href = $(this).data('redirect');
        });

        $(document).ajaxStart(function() { Pace.restart(); });

        // initialize select2 for clone project field
        $("#clone_project").select2({
            theme: 'bootstrap4',
            minimumInputLength: 0,
            ajax: {
                url: '{{api_url().'/v1/admin/projects'}}',
                dataType: 'json',
                type: "GET",
                delay: 500,
                data: function (params) {
                    // Query parameters will be ?search=[term]&type=public&limit=100
                    return {
                        q: params.term,
                        type: 'public',
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    var projects = data.data;
                    return {
                        results: $.map(projects, function (item) {
                            return {
                                text: item.name,
                                id: item.id
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
        function updateIndex(ele) {
            var projects = [];
            $.each($(".project_id:checked"), function(){
                projects.push($(this).val());
            });
            if (projects.length){
                callParams.Type = "POST";
                callParams.Url = api_url + "/projects/update/indexes";
                // Set Data parameters
                dataParams.projects = projects;
                console.log(projects);
                ajaxCall(callParams, dataParams, function (result) {
                    console.log(result);
                });
            }
        }

        // check and uncheck all
        $("#check_all").change(function () {
            $(".project_id:checkbox").prop('checked', $(this).prop("checked"));
        });

    </script>
@endsection

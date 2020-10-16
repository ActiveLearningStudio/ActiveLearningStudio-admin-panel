@extends('adminlte::page')

@section('title', 'All Projects')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">All Projects</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">PROJECTS
                    </h3>
                    <select id="projects_filter" name="projects" class="float-right">
                        <option value="all" selected>All Projects</option>
                        <option value="1">Starter Projects</option>
                        <option value="0">Non Starter</option>
                    </select>
                    <div class="btn-group float-right ">
                        <a class="btn-sm btn-danger ml-1 mr-1 remove_starter d-none" onclick="updateStarter(this, 0)"
                           href="javascript:void(0)">Remove Starter</a>
                        <a class="btn-sm btn-success ml-1 mr-1 make_starter d-none" onclick="updateStarter(this, 1)"
                           href="javascript:void(0)">Make Starter</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped dataTable" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th><input type="checkbox" id="check_all" class="checkbox d-none"> Starter
                                            Project
                                        </th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Original Project</th>
                                        <th>Clone CTR</th>
                                        <th>Status</th>
                                        <th>Indexing</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.base-modal', ['modal' => ['id' => 'preview-project', 'class' => 'modal-xl', 'title' => 'Preview Project']])
@stop
@section('js')
    <script type="text/javascript">
        $(function () {
            var projects_filter = $("#projects_filter");
            var table = {};

            function initializeDataTable(project_type) {
                table = $('.table').DataTable({
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        ['10', '25', '50', '100', 'Show all']
                    ],
                    processing: true,
                    serverSide: true,
                    pageLength: 25,
                    searchDelay: 800,
                    deferRender: true,
                    "ajax": {
                        url: "{{ route('admin.projects.index') }}",
                        data: {
                            mode: project_type,
                        },
                    },
                    columns: [
                        {data: 'starter_project', name: 'starter_project', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email', orderable: false, searchable: false},
                        {data: 'cloned_from', name: 'cloned_from', searchable: false},
                        {data: 'clone_ctr', name: 'clone_ctr', searchable: false},
                        {data: 'status_text', name: 'status_text', searchable: false},
                        {data: 'indexing_text', name: 'indexing_text', searchable: false},
                        {data: 'created_at', name: 'created_at', searchable: false}
                    ],
                    "order": [[1, "desc"]],
                    drawCallback: function (settings, json) {
                        optionsUpdate();
                    }
                });
            }

            // initialize datatable
            initializeDataTable(projects_filter.val());

            // if report mode changes
            projects_filter.on("change", function () {
                table.destroy();
                let mode = projects_filter.val();
                initializeDataTable(mode);
            });

            // check and uncheck all
            $("#check_all").change(function () {
                $(".project_id:checkbox").prop('checked', $(this).prop("checked"));
            });

            // Show/Hide checkboxes and buttons
            function optionsUpdate() {
                let mode = projects_filter.val();
                $(".remove_starter,.make_starter").addClass('d-none');
                if (mode !== 'all') {
                    $(document).find(".checkbox").removeClass('d-none');
                }
                if (mode === '1') {
                    $(".remove_starter").removeClass('d-none');
                } else if (mode === '0') {
                    $(".make_starter").removeClass('d-none');
                }
            }
        });

        // function for updating the starter flag of the project
        function updateStarter(ele, flag) {
            let projects = [];

            $.each($(".project_id"), function () {
                if ($(this).prop("checked")) {
                    projects.push($(this).val());
                }
            });

            resetAjaxParams("POST");
            callParams.Url = api_url + api_v + "/admin/projects/starter/" + flag;
            // Set Data parameters
            dataParams.projects = projects;
            ajaxCall(callParams, dataParams, function (result) {
                if (flag) {
                    $(".project_id:checked").next('.starter_project').text('Yes');
                } else {
                    // $(".project_id").next('.starter_project').text('Yes');
                    $(".project_id:checked").next('.starter_project').text('No');
                }
                $("#projects_filter").trigger('change');
            });
        }
    </script>
@endsection

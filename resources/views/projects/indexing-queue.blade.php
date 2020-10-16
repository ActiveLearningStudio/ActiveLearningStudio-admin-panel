@extends('adminlte::page')

@section('title', 'Indexing Projects')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Requested Indexing</h1>
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
                        <option value="1" selected>REQUESTED</option>
                        <option value="2">NOT APPROVED</option>
                        <option value="3">APPROVED</option>
                    </select>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped dataTable" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>Updated At</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Indexing</th>
                                        <th>Action</th>
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
        var projects_filter = $("#projects_filter");
        var table = {};

        function initializeDataTable(indexing) {
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
                        indexing: indexing,
                    },
                },
                columns: [
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email', orderable: false, searchable: false},
                    {data: 'status_text', name: 'status_text', searchable: false},
                    {data: 'indexing_text', name: 'indexing_text', searchable: false},
                    {data: 'action', name: 'action', searchable: false, orderable: false},
                ],
                "order": [[0, "asc"]],
            });
        }

        // initialize datatable
        initializeDataTable(projects_filter.val());

        // if indexing filter changes
        projects_filter.on("change", function () {
            reBuildDT();
        });

        // destroy the old data-table and intialize new
        function reBuildDT(){
            table.destroy();
            let indexing = projects_filter.val();
            initializeDataTable(indexing);
        }

        /**
         * Update elastic search index for project
         * @param ele
         * @param id
         * @param index
         */
        function updateIndex(ele, id, index) {
            resetAjaxParams();
            callParams.Url = api_url + api_v + "/admin/projects/" + id + "/indexes/" + index;
            ajaxCall(callParams, dataParams, function (){
                reBuildDT();
                updatePreviewModal(ele);
            });
        }
    </script>
@endsection

@extends('adminlte::page')

@section('title', 'User Projects')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">User Projects</h1>
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
                    <h3 class="card-title">PROJECTS</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped dataTable" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Created At</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Original Project</th>
                                        <th>Status</th>
                                        <th>Indexing</th>
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
                    processing: true,
                    serverSide: true,
                    pageLength: 25,
                    searchDelay: 800,
                    deferRender: true,
                    "ajax": {
                        url: "{{ route('admin.projects.index') }}",
                        data: {
                            mode: project_type,
                            exclude_starter: true,
                        },
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'created_at', name: 'created_at', searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email', orderable: false, searchable: false},
                        {data: 'cloned_from', name: 'cloned_from', searchable: false},
                        // {data: 'clone_ctr', name: 'clone_ctr', searchable: false},
                        {data: 'status_text', name: 'status_text', searchable: false},
                        {data: 'indexing_text', name: 'indexing_text', searchable: false},
                    ],
                    "order": [[0, "desc"]],
                });
            }

            // initialize datatable
            initializeDataTable(projects_filter.val());
        });
    </script>
@endsection

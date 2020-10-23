@extends('adminlte::page')

@section('title', 'Queue Monitor')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Queue Monitor</h1>
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
                    <h3 class="card-title">LOGS</h3>
                    <select id="queue_filter" name="projects" class="float-right">
                        <option value="all" selected>All</option>
                        <option value="1">Running</option>
                        <option value="2">Failed</option>
                        <option value="3">Completed</option>
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
                                        <th>Job</th>
                                        <th>Status</th>
                                        <th>Started</th>
                                        <th>Detail</th>
                                        <th>Duration</th>
                                        <th>Error</th>
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
@stop

@section('js')
    <script type="text/javascript">
        $(function () {
            var queue_filter = $("#queue_filter");
            var table = {};


            function initializeDataTable(queue_filter) {
                table = $('.table').DataTable({
                    processing: true,
                    serverSide: true,
                    searchDelay: 800,
                    pageLength: 25,
                    deferRender: true,
                    ajax: {
                        url: "{{ route('admin.queue-monitor.index') }}",
                        data: {
                            filter: queue_filter,
                        },
                    },
                    columns: [
                        {data: 'job_id', name: 'job_id'},
                        {data: 'status', name: 'status', orderable: false, searchable: false},
                        {data: 'started_at', name: 'started_at'},
                        {data: 'detail', name: 'detail', orderable: false, searchable: false},
                        {data: 'time_elapsed', name: 'time_elapsed'},
                        {data: 'exception_message', name: 'exception_message'},
                    ],
                    "order": [[0, "desc"]]
                });
            }

            // initialize datatable
            initializeDataTable(queue_filter.val());

            // if queue filter changes
            queue_filter.on("change", function () {
                table.destroy();
                let filter = queue_filter.val();
                initializeDataTable(filter);
            });
        });
    </script>
@endsection

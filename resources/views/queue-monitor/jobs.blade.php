@extends('adminlte::page')

@section('title', 'Queue Jobs')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Queue Jobs</h1>
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
                    <h3 class="card-title">JOBS</h3>
                    <select id="queue_filter" name="projects" class="float-right">
                        <option value="1" selected>Pending</option>
                        <option value="2">Failed</option>
                    </select>
                    <div class="btn-group float-right ">
                        <a class="btn-sm btn-info ml-1 mr-1 retry_all d-none" onclick="retry_job(this)"
                           href="javascript:void(0)">Retry All</a>
                        <a class="btn-sm btn-danger ml-1 mr-1 forget_all d-none" onclick="forget_job(this)"
                           href="javascript:void(0)">Forget All</a>
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
                                        <th>ID</th>
                                        <th>Queue</th>
                                        <th>Job</th>
                                        <th>Error</th>
                                        <th>Created/Failed</th>
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
@stop

@section('js')
    <script type="text/javascript">
        let queue_filter = $("#queue_filter");
        let table = {};

        function initializeDataTable(queue_filter) {
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 800,
                pageLength: 25,
                deferRender: true,
                ajax: {
                    url: "{{ route('admin.queue-monitor.jobs') }}",
                    data: {
                        filter: queue_filter,
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'queue', name: 'queue'},
                    {data: 'payload', name: 'payload'},
                    {data: 'exception', name: 'exception'},
                    {data: 'time', name: 'time', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "order": [[0, "desc"]]
            });
        }

        // initialize datatable
        initializeDataTable(queue_filter.val());

        // if queue filter changes
        queue_filter.on("change", function () {
            reInitializeDataTable();
            $(".retry_all,.forget_all").toggleClass("d-none");
        });

        /**
         * Re Intialize DataTale
         */
        function reInitializeDataTable() {
            table.destroy();
            let filter = queue_filter.val();
            initializeDataTable(filter);
        }

        /**
         * Retry specific or all job(s) - Push back to queue
         * @param ele
         */
        function retry_job(ele) {
            let jobID = $(ele).hasClass('retry_all') ? 'all' : $(ele).data('id');
            resetAjaxParams();
            callParams.Url = api_url + api_v + "/admin/queue-monitor/jobs/retry/" + jobID;
            ajaxCall(callParams, {}, function (result) {
                reInitializeDataTable();
            });
        }

        /**
         * Delete specific or all job(s)
         * @param ele
         */
        function forget_job(ele) {
            let jobID = $(ele).hasClass('forget_all') ? 'all' : $(ele).data('id');
            resetAjaxParams();
            callParams.Url = api_url + api_v + "/admin/queue-monitor/jobs/forget/" + jobID;
            ajaxCall(callParams, {}, function (result) {
                reInitializeDataTable();
            });
        }
    </script>
@endsection

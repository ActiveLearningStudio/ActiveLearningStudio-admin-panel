@extends('adminlte::page')

@section('title', 'Users Basic Report')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">All Users Report</h1>
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
                    <h3 class="card-title">REPORT</h3>
                    <select id="users_filter" name="users" class="float-right">
                        <option value="all" selected>All Users</option>
                        <option value="subscribed">Subscribed</option>
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
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Projects</th>
                                        <th>Playlists</th>
                                        <th>Activities</th>
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
@section('data-tables-extensions')
    {{--Include Data-Tables extensions CDN--}}
     //this line will not render on front-end, added just to make sure so master blade can include CDN for data-tables
@endsection
@section('js')
    <script type="text/javascript">
        $(function () {
            var report_mode = $("#users_filter");
            var table = {};
            function initializeDataTable(report_type) {
                table = $('.table').DataTable({
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
                    ],
                    buttons: ['print', 'csv',
                        {
                            extend: 'pdfHtml5',
                            customize: function (doc) {
                                doc.content[1].table.widths = ['15%', '15%', '40%', '10%', '10%', '10%'];

                            }
                        }, 'colvis', 'pageLength'],
                    processing: true,
                    serverSide: true,
                    pageLength: 25,
                    searchDelay: 800,
                    deferRender: true,
                    "ajax": {
                        url: "{{ route('admin.users.report.basic') }}",
                        data: {
                            mode: report_type,
                        },
                    },
                    columns: [
                        {data: 'first_name', name: 'first_name'},
                        {data: 'last_name', name: 'last_name'},
                        {data: 'email', name: 'email'},
                        {data: 'projects_count', name: 'projects_count', orderable: false, searchable: false},
                        {data: 'playlists_count', name: 'playlists_count', orderable: false, searchable: false},
                        {data: 'activities_count', name: 'activities_count', orderable: false, searchable: false}
                    ],
                    "order": [[0, "asc"]]
                });
            }

            // initialize datatable
            initializeDataTable(report_mode.val());

            // if report mode changes
            report_mode.on("change", function () {
                table.destroy();
                initializeDataTable(report_mode.val());
            });
        });
    </script>
@endsection

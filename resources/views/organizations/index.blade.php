@extends('adminlte::page')

@section('title', 'Organizations')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Organizations</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a class="btn-sm-app" href="{{route('admin.organizations.create')}}">
                        <i class="fas fa-plus"></i> Add
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
            {{--   <div class="card-header">
                   <h3 class="card-title">ORGANIZATIONS</h3>
               </div>--}}
            <!-- /.card-header -->
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped dataTable" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th>Parent</th>
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
        $(function () {
            var table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                searchDelay: 800,
                deferRender: true,
                ajax: "{{ route('admin.organizations.index') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'image', name: 'image', orderable: false, searchable: false},
                    {data: 'description', name: 'description'},
                    {data: 'parent', name: 'parent', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "order": [[0, "asc"]]
            });
        });

        // destroy the organization
        function destroy_data(id) {
            if (confirm('Are you sure?')) {
                let url = api_url + api_v + "/admin/organizations/" + id;
                destroy(url, id); // url and id parameter for fading the element
            }
        }
    </script>
@endsection

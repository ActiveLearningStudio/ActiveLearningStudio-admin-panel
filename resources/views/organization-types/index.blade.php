@extends('adminlte::page')

@section('title', 'Organization Types')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Organization Types</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a class="btn-sm-app" href="{{url('/admin/organization-types/create')}}">
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
                <div class="card-body">
                    <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-striped dataTable" role="grid">
                                    <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Label</th>
                                        <th>Order</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orgTypes as $type)
                                            <tr>
                                                <td>
                                                    {{$type['id']}}
                                                </td>
                                                <td>
                                                    {{$type['name']}}
                                                </td>
                                                <td>
                                                    {{$type['label']}}
                                                </td>
                                                <td>
                                                    <div class="btn-group-vertical">
                                                        <a 
                                                            class="dropdown-item" 
                                                            href="{{url('admin/organization-types/'.$type['id'].'/order/up')}}"
                                                        >
                                                            <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                                        </a>
                                                        <a 
                                                            class="dropdown-item" 
                                                            href="{{url('admin/organization-types/'.$type['id'].'/order/down')}}"
                                                        >
                                                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a class="btn btn-warning dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Actions
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                            <a class="dropdown-item" href="{{url('admin/organization-types/'.$type['id'].'/edit')}}">Edit</a>
                                                            <a class="dropdown-item" href="{{url('admin/organization-types/'.$type['id'].'/delete')}}">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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

    </script>
@endsection

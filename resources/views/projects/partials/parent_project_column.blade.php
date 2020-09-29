@if( $project['cloned_from'])
<a class="modal-preview" data-target="#preview-project" href="javascript:void(0)"
   data-href="{{route('admin.users.project-preview.modal', $project['cloned_from'])}}">{{$project['cloned_from']}}</a>
@else
    N/A
@endif

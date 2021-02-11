<div class="btn-group action-group">
    <button type="button" class="btn btn-warning">Action</button>
    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
        <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
            <a class="dropdown-item" href="{{route('admin.groups.edit', $group['id'])}}" onclick="location.replace('{{route('admin.groups.edit', $group['id'])}}')">Edit</a>
            <a class="dropdown-item" data-id="{{ $group['id'] }}" onclick="destroy_data('{{ $group['id'] }}')">Delete</a>
        </div>
    </button>
</div>

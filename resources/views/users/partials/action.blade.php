{{--
<div class="btn-group" role="group">
    <a href="{{route('admin.users.edit', $user['id'])}}" class="btn btn-sm btn-info">Edit</a>
    <form action="{{route('admin.users.destroy', $user['id'])}}" method="POST" onsubmit="return confirm('Are you sure?')">
        <button class="btn btn-sm btn-danger" >Delete</button>
    </form>
</div>
--}}
<div class="btn-group">
    <button type="button" class="btn btn-warning">Action</button>
    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
        <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
            <a class="dropdown-item" href="{{route('admin.users.edit', $user['id'])}}" onclick="location.replace('{{route('admin.users.edit', $user['id'])}}')">Edit</a>
            <form action="{{route('admin.users.destroy', $user['id'])}}" method="POST" onsubmit="return confirm('Are you sure?')">
                <a class="dropdown-item" href="javascript:void(0)" onclick="$(this).closest('form').submit();">Delete</a>
                @method('delete')
                @csrf
            </form>
        </div>
    </button>
</div>

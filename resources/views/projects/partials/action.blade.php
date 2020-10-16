<div class="btn-group action-group">
    <button type="button" class="btn btn-warning">Action</button>
    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown"
            aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
        <div class="dropdown-menu" role="menu" x-placement="bottom-start"
             style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
            @if($project['indexing'] === 1 || $project['indexing'] === 2)
                <a class="dropdown-item index-change" onclick="updateIndex(this, '{{ $project['id'] }}', 3)">Approve</a>
            @endif
            @if($project['indexing'] === 1 || $project['indexing'] === 3)
                <a class="dropdown-item index-change" onclick="updateIndex(this, '{{ $project['id'] }}', 2)">Not Approve</a>
            @endif
        </div>
    </button>
</div>

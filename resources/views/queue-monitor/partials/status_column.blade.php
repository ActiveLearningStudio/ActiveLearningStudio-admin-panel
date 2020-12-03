@if(! $job['is_finished'])
    <span class="btn btn-sm btn-primary">Running</span>
@elseif($job['failed'])
    <span class="btn btn-sm btn-danger">Failed</span>
@else
    <span class="btn btn-sm btn-success">Success</span>
@endif

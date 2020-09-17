<!-- Modal -->
<div class="modal fade {{$modal['target'] ?? ''}}" id="{{$modal['id'] ?? ''}}" tabindex="-1" role="dialog"
     aria-labelledby="{{$modal['id'] ?? $modal['target']}}" aria-hidden="true">
    <div class="modal-dialog {{$modal['class'] ?? "modal-dialog-centered"}}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{$modal['title']}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

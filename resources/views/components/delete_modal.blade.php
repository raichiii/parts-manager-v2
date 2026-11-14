<div id="deleteConfirmModal_{{$id}}" class="modal fade" role="dialog" aria-labelledby="deleteConfirmModalLabel_{{$id}}"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header">
                <h5 id="deleteConfirmModalLabel_{{$id}}" class="modal-title text-center">
                    {{ $title }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Body --}}
            <div class="modal-body text-center">
                {{ $text }}
                <br>
                を削除してよろしいですか？
            </div>
            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                <form action="{{$action}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>

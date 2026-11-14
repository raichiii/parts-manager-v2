<div class="card">
    <h5 class="card-header" id="{{ $arialabel }}">
        <a href="" data-toggle="collapse" data-parent="#accordion" aria-expanded="{{ $expand }}" aria-controls="{{ $id }}"
            data-target="#{{ $id }}"><span class="glyphicon glyphicon-folder-close">
            </span>{{ $title }}</a>
    </h5>
    <div id="{{ $id }}" class="collapse" aria-labelledby="{{ $id }}" data-parent="#accordion">
        <div class="card-body">
            <table class="table">
                {{ $table_body }}
            </table>
        </div>
    </div>
</div>

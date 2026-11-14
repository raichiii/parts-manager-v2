<div class="text-center w-100">
    @if ($errors->any())
        <div class="alert alert-danger border border-danger">
                @foreach ($errors->all() as $error)
                {{ $error }}<br/>
                @endforeach
        </div>
    @endif

    @if(session()->has('primary'))
        <div class="alert alert-primary" role="alert">
            {{ session()->get('primary') }}
        </div>
    @endif

    @if(session()->has('secondary'))
        <div class="alert alert-secondary" role="alert">
            {{ session()->get('secondary') }}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('success') }}
        </div>
    @endif

    @if(session()->has('danger'))
        <div class="alert alert-danger" role="alert">
            {!! session()->get('danger') !!}
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="alert alert-warning" role="alert">
            {{ session()->get('warning') }}
        </div>
    @endif

    @if(session()->has('info'))
        <div class="alert alert-info" role="alert">
            {{ session()->get('info') }}
        </div>
    @endif

    @if(session()->has('light'))
        <div class="alert alert-light" role="alert">
            {{ session()->get('light') }}
        </div>
    @endif

    @if(session()->has('dark'))
        <div class="alert alert-dark" role="alert">
            {{ session()->get('dark') }}
        </div>
    @endif
    <style>
        .alert {
            padding: 0px
        }
    </style>
</div>

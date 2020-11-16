@if ($errors->import_learning->any() )
    <div class="text-danger text-left">
        <ul>
            @foreach ($errors->import_learning->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session()->has('error'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ session()->get('error') }}",
                type: "error"
            })
        }
    </script>
@endif

<form method="post" class="d-flex align-items-end justify-content-between" action="{{ route('leads.call.post', $contact) }}">
    @csrf
    <div class="form-group">
        <label>Select a disposition</label>
        @foreach ($dispositions as $disposition)
            <div class="form-check">
                <input 
                    class="form-check-input"
                    type="radio"
                    name="disposition_unreach"
                    id="dispositionUnreach{{ $loop->iteration }}"
                    value="{{ $disposition->id }}"
                    required
                >
                <label class="form-check-label" for="dispositionUnreach{{ $loop->iteration }}">
                    {{ $disposition->name }}
                </label>
            </div>
        @endforeach
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-danger"> Terminate Survery <i class="mdi mdi-minus-box"></i> </button>
    </div>
</form>

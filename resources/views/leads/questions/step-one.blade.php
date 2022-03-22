<form method="post" action="{{ route('leads.call.post', $contact) }}">
    @csrf
    <div class="form-group">
        <label>Call outcome</label>
        @foreach ($DispositionTypes as $DispositionType)
            @continue(($DispositionType->slug != 'reachable' && $DispositionType->slug != 'not-reachable') || $loop->iteration > 2)
            <div class="form-check">
                <input 
                    class="form-check-input"
                    type="radio"
                    name="disposition_type"
                    id="DispositionType{{ $loop->iteration }}"
                    value="{{ $DispositionType->id }}"
                    required
                >
                <label class="form-check-label" for="DispositionType{{ $loop->iteration }}">
                    {{ $DispositionType->name }}
                </label>
            </div>
        @endforeach
    </div>
    <div class="form-group d-flex justify-content-start align-items-center">
        <button type="submit" class="btn btn-success"> Continue <i class="mdi mdi-arrow-right"></i> </button>
    </div>
</form>

<div class="form-group">
    <label for="">Comment Type</label> <br>
    @foreach ($classificationTypes as $classificationType)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="classificationType" id="classificationType{{ $loop->iteration }}" value="{{ $classificationType->id }}" required>
            <label class="form-check-label" for="classificationType{{ $loop->iteration }}">{{ $classificationType->name }}</label>
        </div>
    @endforeach
</div>

<div class="form-group">
    <label for="">Department</label> <br>
    @foreach ($campaignsAll as $campaignsSingle)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="campaignsSingle" id="campaignsSingle{{ $loop->iteration }}" value="{{ $campaignsSingle->id }}" required>
        <label class="form-check-label" for="campaignsSingle{{ $loop->iteration }}">{{ $campaignsSingle->name }}</label>
    </div>
    @endforeach
</div>

<div class="form-group">
    <label for="CommentSummary">Comment Summary</label>
    <select class="form-control" id="CommentSummary" name="classification_id" required>
      <option selected disabled>Choose Comment Type and Department to display Comment Summary</option>
    </select>
</div>

<div class="form-group">
    <label for="">Action Required</label> <br>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="action_required" id="actionYes" value="YES" required>
        <label class="form-check-label" for="actionYes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="action_required" id="actionNo" value="NO" required>
        <label class="form-check-label" for="actionNo">No</label>
    </div>
</div>

@push('scripts')
    <script>
        var classType = false;
        var campaign = false;
        $('input[type=radio][name=classificationType]').on('change', function(){
            classType = $(this).val();
        });
        $('input[type=radio][name=campaignsSingle]').on('change', function(){
            campaign = $(this).val();
        });
        $('input[type=radio][name=classificationType],input[type=radio][name=campaignsSingle]').on('change', function(){
            if(classType && campaign){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('leads.comment.summary') }}",
                    type: "POST",
                    cache: false,
                    data: {classType:classType, campaign: campaign},
                    success: function(result){
                        var options = '';
                        for (let option of result) {
                            options += "<option value=" + option.id + ">" + option.name + "</option>";
                        }
                        $("#CommentSummary").html(options);
                    }
                });
            }
            
        });
    </script>
@endpush
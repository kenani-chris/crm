<form method="post" action="{{ route('leads.call.post', $contact) }}">
    @csrf
    <div class="form-group">
        <label>
            Q.{{ session('questionNo') }} :
            @php
                $hour = date('H');
                $dayTerm = ($hour > 17) ? "Evening" : (($hour > 12) ? "Afternoon" : "Morning");
            @endphp
            {{ str_replace(
                [
                    "Good Morning/Good Evening/Good Afternoon",
                    "(Agent Name)",
                    "Mr./Mrs./Miss.",
                    "good day/afternoon/evening"
                ],
                [
                    "Good " . $dayTerm, 
                    auth()->user()->name,
                    !empty($contact->customer_description) ? $contact->customer_description : '( N/A )',
                    "Good " . $dayTerm,
                ],
                $question->question
            ) }} 
            
        </label>
        @if ($answers->isEmpty())
            <input type="hidden" name="survey_completed" value="true">
        @else
            @foreach ($answers as $answer)
                @if($answer->has_text_box)
                    @if($answer->go_to_voc)
                            <div class="form-check">
                                <input 
                                    class="form-check-input"
                                    type="radio"
                                    name="answer"
                                    id="dispositionreachvoc{{ $loop->iteration }}"
                                    value="{{ $answer->id }}"
                                    required
                                >
                                <label class="form-check-label" for="dispositionreachvoc{{ $loop->iteration }}">
                                    {{ $answer->answer }}
                                </label>
                            </div>
                            @if($loop->last)
                                <div class="form-group mt-2">
                                    <label>Kindly share with us your experience of the services we provided</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        {{-- id="dispositionUnreach{{ $loop->iteration }}" --}}
                                        name="answer_text"
                                        required
                                    >
                                    {{-- <input type="hidden" name="answer" value="{{ $answer->id }}"> --}}
                                </div>
                                @include('leads.questions.reachable-questions.voc')
                                @include('leads.questions.reachable-questions.awareness-creation')
                            @endif
                    @else
                        <div class="form-group">
                            {{-- <label for="dispositionUnreach{{ $loop->iteration }}">{{ $answer->answer }}</label> --}}
                            <input
                                class="form-control"
                                type="text"
                                {{-- id="dispositionUnreach{{ $loop->iteration }}" --}}
                                name="answer_text"
                                required
                            >
                            <input type="hidden" name="answer" value="{{ $answer->id }}">
                        </div>
                        @include('leads.questions.reachable-questions.voc')  
                        @break
                    @endif
                @elseif ($answer->answer =='Ask the customer for a convenient time.')
                    <div class="form-group">
                        {{-- <label for="dispositionUnreach{{ $loop->iteration }}">{{ $answer->answer }}</label> --}}
                        <input
                            class="form-control dateTimePicker"
                            type="datetime-local"
                            {{-- id="dispositionUnreach{{ $loop->iteration }}" --}}
                            name="answer_date"
                            required
                        >
                        <input type="hidden" name="answer" value="{{ $answer->id }}">
                    </div>
                    @break
                @else
                    <div class="form-check">
                        <input 
                            class="form-check-input"
                            type="radio"
                            name="answer"
                            id="dispositionreach{{ $loop->iteration }}"
                            value="{{ $answer->id }}"
                            required
                        >
                        <label class="form-check-label" for="dispositionreach{{ $loop->iteration }}">
                            {{ $answer->answer }}
                        </label>
                    </div>
                @endif
            @endforeach
        @endif
        
    </div>
    <div class="form-group d-flex justify-content-{{ ($answers->isEmpty()) ? 'end' : 'between' }} align-items-center">
            @if ($answers->isEmpty())
                <button type="submit" class="btn btn-success">
                    Complete Survey <i class="mdi mdi-arrow-right"></i>
                </button>
            @else
                <button type="submit" name="action" value="save" class="btn btn-success">
                    Continue <i class="mdi mdi-arrow-right"></i>
                </button>
                <button name="action" value="terminate" class="btn btn-danger">
                    Terminate Survery <i class="mdi mdi-minus-box"></i>
                </button>
            @endif
    </div>
</form>

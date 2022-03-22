
                               

<form action="{{route('resolutions.search')}}" role="form" method="post" id="search-form">


    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label for="date_from">Date from</label>
                <input 
                    type="text" 
                    id="date_from" 
                    class="form-control dateTimePicker" 
                    name="date_from" 
                    autocomplete="off"
                    {{ (!empty(old('date_from'))) ? 'value=' . old('date_from') : '' }}
                >
            </div>
        </div>
    
        <div class="col-sm-3">
            <div class="form-group">
                <label for="date_to">Date To</label>
                <input 
                    type="text" 
                    id="date_to" 
                    class="form-control dateTimePicker" 
                    name="date_to" 
                    autocomplete="off"
                    {{ (!empty(old('date_to'))) ? 'value=' . old('date_to') : '' }}
                >
            </div>
        </div>
    
        <div class="col-sm-3">
            <div class="form-group">
                <label for="campaign">Department</label>
                <select class="form-control" name="campaign" id="campaign">
                <option value="">All</option>
                        @foreach($campaigns as $campaign)
                            <option 
                                value="{{$campaign->id}}"
                                {{ ( !empty(old('campaign')) && old('campaign') == $campaign->id) ? 'selected' : '' }}
                            >
                                {{$campaign->name}}
                            </option>
                        @endforeach
                </select>
                
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="branch">Branch</label>
                <select class="form-control" name="branch" id="branch">
                <option value="">All</option>
                        @foreach($branches as $branch)
                            <option 
                                value="{{$branch->id}}"
                                {{ ( !empty(old('branch')) && old('branch') == $branch->id) ? 'selected' : '' }}
                            >
                                {{$branch->name}}
                            </option>
    
                        @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="brand">Brand</label>
                <select class="form-control" name="brand" id="brand">
                <option value="">All</option>
                        @foreach($brands as $brand)
                        <option
                            value="{{$brand->id}}"
                            {{ ( !empty(old('brand')) && old('brand') == $brand->id) ? 'selected' : '' }}
                        >
                            {{$brand->name}}
                        </option>
    
                        @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
          
                <label for="advisor">Advisor/Consultant</label>
                <select class="form-control" name="advisor" id="advisor">
                <option value="">All</option>
                        @foreach($advisors as $advisor)
                        <option
                            value="{{$advisor->id}}"
                            {{ ( !empty(old('advisor')) && old('advisor') == $advisor->id) ? 'selected' : '' }}
                        >
                            {{$advisor->name}}
                        </option>
                        @endforeach
                </select>
            </div>
        </div>
        @if(Request::is('customer-feedbacks') )
    
        <div class="col-sm-3">
            <div class="form-group">
                <label for="feedback">Feedback Type</label>
            <select class="form-control" name="feedback" id="feedback">
                <option value="">All</option>
                        @foreach($feedbacks as $feedback)
                        <option value="{{$feedback->id}}">{{$feedback->name}}</option>
    
                        @endforeach
                </select>
            </div>
        </div>
        @else
        <div class="col-sm-3"></div>
        @endif
        
       
        <div class="col-sm-3">
        <label></label>
        <div class="form-group" style="margin-top:10px">
        <button type="submit" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> Filter</button>
    </div>
    
        
    </div>
    </div>
    @csrf
    
    </form>
    
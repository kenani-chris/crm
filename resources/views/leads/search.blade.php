
@if (isset($callback))
    <form action="{{ route('leads.user.callback.filter') }}" role="form" method="post">
@elseif(isset($unreachable))
    <form action="{{ route('leads.user.unreachable.filter') }}" role="form" method="post">
@else
    <form action="{{ route('leads.user.filter') }}" role="form" method="post">
@endif
    @csrf
    <div class="d-flex align-item-center">
            <div class="w-75 d-flex align-item-center">
                <div class="form-group w-50 pr-4">
                    <label for="campaign-filter">Channels</label>
                    <select id="campaign-filter" class="form-control" name="campaign_filter">
                        <option disabled selected>Select Channel</option>
                        @foreach($campaigns as $campaign)
                            <option 
                                value="{{$campaign->id}}"
                                {{($campaign->name=="Service" || $campaign->name=="Sales" ||  $campaign->name=="Body Shop" ||  $campaign->name=="Parts") ? "" : "disabled"}}
                                {{ ( !empty(old('campaign_filter')) && old('campaign_filter') == $campaign->id) ? 'selected' : '' }}
                            >
                                {{$campaign->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group w-50 pr-4">
                    <label for="phone">Search Telephone #</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="phone"
                        name="phone"
                        {{ ( !empty(old('phone'))) ? 'value=' . old('phone') : '' }}
                    >
                </div>
            </div>
            
    
            <div class="form-group w-25">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
    </div>
    
    </form>
    
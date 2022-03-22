
<form action="{{ route('leads.tracker.post') }}" role="form" method="post" id="search-form">
    @csrf
    <div class="row">
        <div class="form-group col-6 col-md-4">
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
        <div class="form-group col-6 col-md-4">
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
        <div class="form-group col-6 col-md-4">
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
        <div class="form-group col-6 col-md-4">
            <label for="branch-filter">Branch</label>
            <select id="branch-filter" class="form-control" name="branch_filter">
                <option disabled selected>Select Branch</option>
                @foreach($branches as $branch)
                    <option 
                        value="{{$branch->id}}"
                        {{ ( !empty(old('branch_filter')) && old('branch_filter') == $branch->id) ? 'selected' : '' }}
                    >
                        {{$branch->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="brand-filter">Brand</label>
            <select id="brand-filter" class="form-control" name="brand_filter">
                <option disabled selected>Select Brand</option>
                @foreach($brands as $brand)
                    <option 
                        value="{{$brand->id}}"
                        {{ ( !empty(old('brand_filter')) && old('brand_filter') == $brand->id) ? 'selected' : '' }}
                    >
                        {{$brand->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-6 col-md-4">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> Filter</button>
        </div>
    </div>
    
    </form>
    
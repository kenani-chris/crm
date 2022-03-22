<form action="javascript::void()" role="form" method="post" id="dashborad-search-form" style="display:none">

<div class="row">
<div class="col-sm-2">
        <div class="form-group">
            <label for="campaign_id">Survey</label>

            <select name="campaign_id" id="campaign_id" class="form-control">
                <option value="">All</option>
                    @foreach( $surveys as $survey)
                        <option value="{{$survey->id}}">{{$survey->name}}</option>
                    @endforeach
            </select>
        </div>
    </div>
   
    <div class="col-sm-2">
        <div class="form-group">
            <label for="branch_id">Branch</label>

<select name="branch_id" id="branch_id" class="form-control">
<option value="">All</option>
            @foreach( $branches as $branch)
                <option value="{{$branch->id}}">{{$branch->name}}</option>
            @endforeach

            </select>
           
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label for="brand_id">Brand</label>

            <select name="brand_id" id="brand_id" class="form-control">
                <option value="">All</option>
                    @foreach( $brands as $brand)
                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                    @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label for="from_date">Date from</label>
            <input type="text" id="date_from" class="form-control dateTimePicker" name="from_date">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label for="to_date">Date To</label>
            <input type="text" id="date_to" class="form-control dateTimePicker" name="to_date">
        </div>
    </div>
   
    <div class="col-sm-2">
    <div class="form-group" style="margin-top:30px">
    <button type="button" class="btn btn-primary " id="search">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i> Filter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
</div>
</div>
@csrf

</div>

</form>


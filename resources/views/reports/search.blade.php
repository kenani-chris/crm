<form action="{{ route('reports.overallcsi.post') }}" role="form" method="post" id="search-form">
    @csrf
    <div class="row">
        <div class="col-sm-2">
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
        <div class="col-sm-2">
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
                <label for="branch">Branch</label>
                <select name="branch" id="branch" class="form-control">
                    <option value="">All</option>
                    @foreach( $branches as $branch)
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
        <div class="col-sm-3" id="brand-parent">
            <div class="form-group">
                <label for="brand">Brand</label>
                <select name="brand" id="brand" class="form-control">
                    <option value="">All</option>
                        @foreach( $brands as $brand)
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
        @if (isset($advisors))
            <div class="col-sm-3" id="advisor-parent" style="display: none">
                <div class="form-group">
                    <label for="advisor">Service Advisor</label>
                    <select name="advisor" id="advisor" class="form-control">
                        <option value="">All</option>
                            @foreach( $advisors as $advisor)
                                <option value="{{$advisor->pf_no}}">{{$advisor->name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="col-sm-2">
            <div class="form-group" style="margin-top:30px">
                <button type="submit" class="btn btn-primary btn-block pull-right"><i class="fa fa-search"></i> Filter</button>
            </div>
        </div>
    </div>
</form>

@extends('layouts.app')

@section('pageParent', 'Leads')

@section('pageTitle', $campaignName . ' Channel')

@section('content')
<div class="card">
    <div class="card-body">
    <div class="col-md-12"><br></div>
        <div class="table-responsive">
            <table id="resolutionRate" class="table table-centered table-nowrap table-striped table-bordered " style='border-collapse: collapse;'>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Description</th>
                        <th>Order Number</th>
                        <th>License Plate</th>
                        <th>Telephone 1</th>
                        <th>Telephone 2</th>
                        <th>Branch</th>
                        <th>Brand</th>
                        <th>Last Contacted On</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $contact->customer }}</td>
                        <td>{{ $contact->customer_description }}</td>
                        <td>{{ $contact->order_number }}</td>
                        <td>{{ $contact->license_plate_number }}</td>
                        <td>{{ $contact->telephone_one }}</td>
                        <td>{{ $contact->telephone_two }}</td>
                        <td>{{ $branchName}}</td>
                        <td>{{ $brandName }}</td>
                        <td>
                            @isset($lastCalled)
                                {{ $lastCalled }}
                            @endisset
                            @empty($lastCalled)
                                <span class="text-warning">Not Contacted</span>
                            @endempty
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="col-6 form-group">
                <label for="tele1">Telephone # 1</label>
                <input type="text" readonly id="tele1" class="form-control" value="{{ $contact->telephone_one }}">
            </div>
            <div class="col-6 form-group">
                <label for="tele2">Telephone # 2</label>
                <input type="text" readonly id="tele2" class="form-control" value="{{ $contact->telephone_two }}">
            </div>
        </div>
        <hr />

        <div class="row mt-3">
            <div class="col-12">
                @if (isset($step) && $step == "step-two-unreach")
                    @include('leads.questions.step-two-unreach')
                @endif

                @if (isset($step) && $step == "step-two-reach-terminate")
                    @include('leads.questions.step-two-reach-terminate')
                @endif 

                @if (isset($step) && $step == "reachable-questions")
                    @include('leads.questions.reachable-questions.questions')
                @endif 
                
                @empty($step)
                    @include('leads.questions.step-one')
                @endempty

                
            </div>
        </div>
    </div>
</div>

@endsection
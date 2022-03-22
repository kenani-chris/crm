
                                   <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-right">
                                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </a>
                                           
                                        </div>
                                        <h4 class="header-title mb-3">10 Recently Completed Catalogs</h4>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-sm table-nowrap table-centered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Brand Name</th>
                                                        <th>Catalog</th>
                                                        <th>Date Assigned</th>
                                                        <th>Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                               {{-- @foreach($mytickets as $myticket)

                                                <?php
$name=explode(' ',$myticket->customer->name);
                                                ?>
                                                    <tr>
                                                        <td class="row">
<div class="col-md-1">
<img  width="30" height="30" avatar="{{$name[0]}}">
</div>
<div class="col-md-10">
                                                        
                                                            <h5 class="font-15 my-1 font-weight-normal">{{ucwords($myticket->customer->name)}} (via <span class="text-success text-mute">{{$myticket->channel}}</span>)</h5>
                                                            <span class="text-muted mb-1 d-block font-13">{{$myticket->workflow->type_of_issue}}</span>
                                                       </div> 
                                                        
                                                        </td>
                                                        <td><h5 class="font-15 my-1 font-weight-normal">{{$myticket->ticketNo}}</h5>
                                                        <span class="text-muted mb-1 d-block font-13">{{$myticket->created_at}}</span>
                                                        </td>
                                                        <td>{{$myticket->priority}}</td>
                                                        <td> {{$myticket->currentStatus}}
                                                            @if(Carbon\Carbon::now()<$myticket->tat_at && $myticket->currentStatus!='Closed')
                                                            <span class="badge badge-success">{{'Ontime'}}</span>
                                                            @elseif(Carbon\Carbon::now()>=$myticket->tat_at && $myticket->currentStatus!='Closed')
                                                                <span class="badge badge-danger">{{'Overdue'}}</span>
                                                            @else
                                                            <span class="badge badge-primary">{{$myticket->currentStatus}}</span>
                                                            @endif</td>
                                                        <td class="table-action">
                                                            <a href="{{route('ticketEdit',$myticket->id)}}" class="btn btn-sm btn-info"> <i class="mdi mdi-square-edit-outline"></i> Action</a>

                                                       
                                                        </td>
                                                    </tr>
                                                @endforeach --}}
                                                </tbody>
                                            </table>
                                        </div> <!-- end table-responsive-->

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <!-- end col-->

                          
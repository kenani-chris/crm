<!doctype html>
<html lang="en">
<head>
  	<title>{{ !empty($contact->customer_description) ? $contact->customer_description : 'N/A' }} {{ !empty($campaign->name) ? $campaign->name : 'N/A'}}  Negative VOC notification</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-5">
                <h2 >{{ !empty($contact->customer_description) ? $contact->customer_description : 'N/A' }} {{ !empty($campaign->name) ? $campaign->name : 'N/A'}} Negative VOC notification</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Ticket No</th>
                            <td>{{ !empty($contact->order_number) ? $contact->order_number : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ !empty($contact->customer_description) ? $contact->customer_description : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Order Number</th>
                            <td>{{ !empty($contact->order_number) ? $contact->order_number : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Telephone One</th>
                            <td>{{ !empty($contact->telephone_one) ? $contact->telephone_one : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Telephone Two</th>
                            <td>{{ !empty($contact->telephone_two) ? $contact->telephone_two : 'N/A'}}</td>
                        </tr>
                        <tr>
                            <th>Advisor/Consultant</th>
                            <td>{{ !empty($mailAdvisor->name) ? $mailAdvisor->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dept</th>
                            <td>{{ !empty($campaign->name) ? $campaign->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Branch</th>
                            <td>{{ !empty($branch->name) ? $branch->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td>{{ !empty($brand->name) ? $brand->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>VOC</th>
                            <td>{{ !empty($request->answer_text) ? $request->answer_text : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if (empty($member->toyota_case->comments) && ($mailClassificationType->slug == "enquiries" || $mailClassificationType->slug == "negative"))
                                    Open
                                @else
                                    Closed
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Feedback Type</th>
                            <td>{{ !empty($mailClassificationType->name) ? $mailClassificationType->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Comment Summary</th>
                            <td>{{ !empty($mailClassification->name) ? $mailClassification->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $date }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $time }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

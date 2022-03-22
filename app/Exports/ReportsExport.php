<?php

namespace App\Exports;

use App\Ticket;
use App\Workflow;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class ReportsExport implements FromQuery, WithHeadings,WithMapping
{
    use Exportable;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Ticket::query();
    }

    /**
    * @var Ticket $customer
    */
    public function map($ticket): array
    {

        /*"Customers Account Name",
        "Customer A/c No",
        "Phone No",
        "Ticket No",
        "Query/Request",
        "Priority",
        "Case Summary",
        "Followup & Resolution Comments",
        "Date Received",
        "Department",
        "Receipt Mode",
        "Status",
        "Resolution Date",
        "Days taken to resolve",
        "Consultant Name",
        "Escalated To"*/

        if($ticket->escalate=='Yes'){
            $escalated=$ticket->workflow->escalationEmail;
        }else{
            $escalated="";
        }

        if($ticket->currentStatus=='Closed'){
            $updated_at=$ticket->updated_at;
        }else{
            $updated_at="";
        }
       
        return [
            $ticket->customer['name'],
            $ticket->customer['account_no'],
            $ticket->customer['phone_no'],
            $ticket->ticketNo,
            $ticket->workflow->type_of_issue,
            $ticket->priority,
            $ticket->comments,
            $ticket->comments,
            $ticket->created_at,
            $ticket->workflow->department,
            $ticket->channel,
            $ticket->currentStatus,
            $updated_at,
            $ticket->user->name,
            $escalated
        ];
    }

    public function headings(): array
    {

        return ["Customers Account Name","Customer A/c No","Phone No","Ticket No","Query/Request","Priority","Case Summary","Followup & Resolution Comments","Date Received","Department","Receipt Mode","Status","Resolution Date","Consultant Name","Escalated To"];
    }
}

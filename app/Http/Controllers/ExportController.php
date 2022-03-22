<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CSIReportExport;
use App\Exports\NPSReportExport;
use App\Exports\VOCReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NPSScoreReportExport;
use App\Exports\AdvisorCSIReportExport;
use App\Exports\CustomerFeedbackExport;
use App\Exports\OverallCSIReportExport;
use App\Exports\BodyMonthlyReportExport;
use App\Exports\PartsMonthlyReportExport;
use App\Exports\SalesMonthlyReportExport;
use App\Exports\ServiceMonthlyReportExport;

class ExportController extends Controller
{
    public function __construct(){

        $this->middleware('auth');
    }


    public function customerFeedbacksExport(Request $request){
        ini_set('max_execution_time', 3000);

        return Excel::download(new CustomerFeedbackExport($request), 'CustomerFeedbackExport.xlsx');
    }

    public function salesMonthlyReport(Request $request, $id){
        ini_set('max_execution_time', 3000);

        return Excel::download(new SalesMonthlyReportExport($request,$id), 'SalesMonthlyReportExport.xlsx');
    }

    public function bodyMonthlyReport(Request $request, $id){
        ini_set('max_execution_time', 3000);

        return Excel::download(new BodyMonthlyReportExport($request,$id), 'BodyShopMonthlyReportExport.xlsx');
    }

    public function partsMonthlyReport(Request $request, $id){
        ini_set('max_execution_time', 3000); 

        return Excel::download(new PartsMonthlyReportExport($request,$id), 'PartsMonthlyReportExport.xlsx');
    }
    
    public function serviceMonthlyReport(Request $request, $id){
        ini_set('max_execution_time', 3000); 

        return Excel::download(new ServiceMonthlyReportExport($request,$id), 'ServiceMonthlyReportExport.xlsx');
    }

    public function VOCReport(Request $request, $id){
        ini_set('max_execution_time', 3000);

        return Excel::download(new VOCReportExport($request,$id), 'VOCReport.xlsx');
    }

    public function CSIReport(Request $request, $id){
        ini_set('max_execution_time', 3000);

        return Excel::download(new CSIReportExport($request,$id), 'CSIReport.xlsx');
    }

    public function NPSReport(Request $request, $id){
        ini_set('max_execution_time', 3000);

        return Excel::download(new NPSReportExport($request,$id), 'NPSReport.xlsx');
    }

    public function AdvisorCSIReport(Request $request, $id){
        ini_set('max_execution_time', 3000);

        return Excel::download(new AdvisorCSIReportExport($request,$id), 'AdvisorCSIReportExport.xlsx');
    }

    public function overallCSIReport(Request $request){
        ini_set('max_execution_time', 3000);

        return Excel::download(new OverallCSIReportExport($request), 'OverallCSIReportExport.xlsx');
    }
    
    public function npsScoreReport(Request $request){
        ini_set('max_execution_time', 3000);

        return Excel::download(new NPSScoreReportExport($request), 'NPSCallReportExport.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\EmployeeAvailabilityExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function export()
    {
        return Excel::download(new EmployeeAvailabilityExport, 'reporte_empleados.xlsx');
    }
}

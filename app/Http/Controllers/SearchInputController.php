<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchInputController extends Controller
{
    //
    public function searchCheckFrom(Request $request)
    {
        $result = Department::where('department', 'LIKE', '%' . $request->search . '%')
            ->limit(5)
            ->get();

        return response()->json($result);
    }

    public function searchBankName(Request $request)
    {
        $result = Bank::where('bankbranchname', 'LIKE', '%' . $request->search . '%')->limit(5)->get();
        return response()->json($result);
    }
    public function searchCustomerName(Request $request)
    {
        $result = Customer::where('fullname', 'LIKE', '%' . $request->search . '%')
            ->where('is_blacklist', '=', '0')->limit(5)->get();

        return response()->json($result);
    }
    public function searchEmployee(Request $request)
    {
        $result = DB::connection('pis')
            ->table('employee3')
            ->join('applicant', 'app_id', '=', 'emp_id')
            // ->whereIn('startdate', ['2025-01-01', '2025-03-01'])
            ->where('emp_type', 'ojt')
            ->where('current_status', 'active')
            ->where('school', 'like', '%' .$request->search.'%')
            ->limit(100)
            ->get();

            dd($result->toArray());

        return response()->json($result);
    }

    public function searchBunit(Request $request)
    {
        $results = BusinessUnit::where('bname', 'like', '%' . $request->search . '%')
            ->limit(5)
            ->get();

        return response()->json($results);
    }
    public function searchCompany(Request $request)
    {
        $results = Company::where('company', 'like', '%' . $request->search . '%')
            ->limit(5)
            ->get();

        return response()->json($results);
    }
}

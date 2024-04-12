<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UserType;
use App\Models\BusinessUnit;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Style\Border;


class UserController extends Controller
{
    public function index()
    {
        $users = User::join('company', 'company.company_id', '=', 'users.company_id')
            ->join('department', 'department.department_id', '=', 'users.department_id')
            ->join('businessunit', 'businessunit.businessunit_id', '=', 'users.businessunit_id')
            ->join('usertype', 'usertype.usertype_id', '=', 'users.usertype_id')
            ->select('users.*', 'company.*', 'department.*', 'businessunit.*', 'usertype.*')
            ->orderBy('users.created_at', 'desc')
            ->paginate(12)->withQueryString();

        $userType = UserType::all();

        return Inertia::render('Users/Index', [
            'get_users' => $users,
            'userType' => $userType,
        ]);
    }
    public function createUser(Request $request)
    {

        // dd($request->name);

        $request->validate([
            'name' => 'required',
            'empid' => 'required',
            'username' => 'required',
            'ContactNo' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
            'usertype_id' => 'required',
            'businessunit_id' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'empid' => $request->empid,
            'username' => $request->username,
            'ContactNo' => $request->ContactNo,
            'company_id' => $request->company_id,
            'department_id' => $request->department_id,
            'usertype_id' => $request->usertype_id,
            'businessunit_id' => $request->businessunit_id,
            'password' => Hash::make('123456'),
            'user_status' => 'active',
        ]);

        return redirect()->back();
    }
    public function updateUser(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'ContactNo' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
            'usertype_id' => 'required',
            'businessunit_id' => 'required',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->ContactNo = $request->ContactNo;
        $user->company_id = $request->company_id;
        $user->department_id = $request->department_id;
        $user->usertype_id = $request->usertype_id;
        $user->businessunit_id = $request->businessunit_id;


        $user->update();

        return redirect()->back();
    }



    public function searchCompany(Request $request): Response
    {
        $query = $request->input('query');

        // Use your model to fetch data based on the $query
        $results = Company::where('company', 'like', "%$query%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    public function searchBunit(Request $request): Response
    {
        $query = $request->input('query');

        // Use your model to fetch data based on the $query
        $results = BusinessUnit::where('bname', 'like', "%$query%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    public function searchDepartment(Request $request): Response
    {
        $query = $request->input('query');

        // Use your model to fetch data based on the $query
        $results = Department::where('department', 'like', "%$query%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    public function exportExcel()
    {
        // dd(1);
        $users = User::join('company', 'company.company_id', '=', 'users.company_id')
            ->join('department', 'department.department_id', '=', 'users.department_id')
            ->join('businessunit', 'businessunit.businessunit_id', '=', 'users.businessunit_id')
            ->join('usertype', 'usertype.usertype_id', '=', 'users.usertype_id')
            ->select('users.*', 'company.*', 'department.*', 'businessunit.*', 'usertype.*')
            ->orderBy('users.updated_at', 'desc')
            ->cursor();

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();


        $headerRow = [
            'Name',
            'Username',
            'Company',
            'Business Unit',
            'Contact No',
            'Department',
            'User Type',
            'Status',
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $spreadsheet->getActiveSheet()->fromArray($headerRow, null, 'A1');

        $row = 2;
        foreach ($users as $user) {

            $userData = [
                $user->name,
                $user->username,
                $user->company,
                $user->bname,
                $user->ContactNo,
                $user->department,
                $user->usertype_name,
                $user->user_status,
            ];

            $spreadsheet->getActiveSheet()->fromArray($userData, null, "A$row");

            foreach (range('A', 'H') as $column) {
                $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);

                // Apply border to each cell
                $spreadsheet->getActiveSheet()->getStyle($column . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }

            $row++;
        }

        // Create a temporary file to store the spreadsheet
        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = 'UsersData_' . now()->format('M, d Y') . '.xlsx';

        // Download the file
        return response()->download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }


    public function resignReactive(Request $request)
    {
        // dd($request->user);
        $user = User::findOrFail($request->userId);

        // dd($user->toArray());

        if ($user->user_status == 'active') {
            $user->user_status = 'resigned';
        } else {
            $user->user_status = 'active';
        }

        $user->update();

        return redirect()->back();

    }

    public function userDetails(Request $request, $id)
    {
        $user = User::join('company', 'company.company_id', '=', 'users.company_id')
            ->join('department', 'department.department_id', '=', 'users.department_id')
            ->join('businessunit', 'businessunit.businessunit_id', '=', 'users.businessunit_id')
            ->join('usertype', 'usertype.usertype_id', '=', 'users.usertype_id')
            ->where('users.id', $id)
            ->select('users.*', 'company.*', 'department.*', 'businessunit.*', 'usertype.*')
            ->orderBy('users.created_at', 'desc')
            ->firstOrFail();

        // dd($user);

        return Inertia::render('Users/UsersDetails', [
            'user' => $user,
        ]);
    }



}

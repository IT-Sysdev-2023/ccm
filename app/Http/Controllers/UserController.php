<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersCreateRequest;
use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function index()
    {

        $users = User::where('empid', '!=', '')->with([
            'company' => function ($query) {
                $query->select('company_id', 'company');
            },
            'departments' => function ($query) {
                $query->select('department_id', 'department');
            },
            'businessunit' => function ($query) {
                $query->select('businessunit_id', 'bname');
            },
            'employee3' => function ($query) {
                $query->select('emp_no', 'emp_id');
            },
            'employee3.applicant' => function ($query) {
                $query->select('app_id', 'photo');
            },
        ])->orderBy('users.created_at', 'DESC')->paginate(12)->withQueryString();

        $userType = UserType::all();

        return Inertia::render('Users/Index', [
            'get_users' => $users,
            'userType' => $userType,
        ]);
    }
    public function settings()
    {

        return Inertia::render("Users/Setting");
    }

    public function updateUsername(Request $request)
    {
        User::where('id', $request->user()->id)->update(['username' => $request->username]);
        return redirect()->back();
    }

    public function createUser(UsersCreateRequest $request)
    {
        $request->validated();

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
    public function updateUser(Request $request): RedirectResponse
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

        User::where('id', $request->userId)->update([
            'name' => $request->name,
            'username' => $request->username,
            'ContactNo' => $request->ContactNo,
            'company_id' => $request->company_id,
            'department_id' => $request->department_id,
            'usertype_id' => $request->usertype_id,
            'businessunit_id' => $request->businessunit_id,
        ]);

        return redirect()->back();
    }

    public function searchCompany(Request $request)
    {
        $query = $request->input('query');

        $results = Company::where('company', 'like', "%$query%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    public function searchBunit(Request $request)
    {
        $query = $request->input('query');

        $results = BusinessUnit::where('bname', 'like', "%$query%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    public function searchDepartment(Request $request)
    {
        $query = $request->input('query');

        $results = Department::where('department', 'like', "%$query%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    public function exportExcel()
    {
        $users = User::join('company', 'company.company_id', '=', 'users.company_id')
            ->join('department', 'department.department_id', '=', 'users.department_id')
            ->join('businessunit', 'businessunit.businessunit_id', '=', 'users.businessunit_id')
            ->join('usertype', 'usertype.usertype_id', '=', 'users.usertype_id')
            ->select('users.*', 'company.*', 'department.*', 'businessunit.*', 'usertype.*')
            ->orderBy('users.updated_at', 'desc')
            ->cursor();
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
        $tempFilePath = tempnam(sys_get_temp_dir(), 'excel_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        $filename = 'UsersData_' . now()->format('M, d Y') . '.xlsx';

        return response()->download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }

    public function resignReactive(Request $request)
    {
        $user = User::findOrFail($request->userId);

        if ($user->user_status == 'active') {
            $user->user_status = 'resigned';
        } else {
            $user->user_status = 'active';
        }
        $user->update();
        return redirect()->back();
    }

    public function userDetails($id)
    {
        $user = User::where('id', $id)->with([
            'company' => function ($query) {
                $query->select('company_id', 'company', 'acroname');
            },
            'departments' => function ($query) {
                $query->select('department_id', 'department');
            },
            'businessunit' => function ($query) {
                $query->select('businessunit_id', 'bname');
            },
            'employee3' => function ($query) {
                $query->select('emp_no', 'emp_id', 'emp_type', 'position');
            },
            'employee3.applicant' => function ($query) {
                $query->select('app_id', 'photo', 'hobbies', 'home_address', 'specialSkills');
            },
        ])->first();

        if ($user && $user->employee3 && $user->employee3->applicant && $user->employee3->applicant->photo) {

            $photoPath = $user->employee3->applicant->photo;
            $photoContent = file_get_contents('http://172.16.161.34:8080/hrms' . $photoPath);

            $extension = pathinfo($photoPath, PATHINFO_EXTENSION);

            $filename = $user->id;

            $path = 'users-image/' . $filename;

            if (!Storage::disk('public')->exists($path)) {

                $photoContent = file_get_contents('http://172.16.161.34:8080/hrms' . $photoPath);

                Storage::disk('public')->put($path, $photoContent);
            }
        }

        return Inertia::render('Users/UsersDetails', [
            'user' => $user,
        ]);
    }

    public function getUsersApplicantEmployee3()
    {

        $users = DB::connection('pis')->table('applicant')->limit(100)->get();

        dd($users);
    }
}

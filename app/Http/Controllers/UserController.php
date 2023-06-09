<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Response;
use \PDF;

class UserController extends Controller
{
    public function home(){
        $notifications=DB::table('notifications')->where('isSeenStudent', '=', 0)->get();
        return view("welcome", ['notifications'=>$notifications]);
    }

    public function login(){
        return view("user.login");
    }

    public function adminLogin(){
        return view("admin.login");
    }

    public function adminAuthenticate(Request $req){
        $validated=$req->validate([
            'name'=>'required',
            'password'=>'required',
        ]);

        if(auth()->attempt($validated)){
            $user=DB::table('users')->where('name', '=', $req->name)->first();
            if($user != null){
                if($user->account_type == 'admin'){
                    $req->session()->regenerate();
                    return redirect("/admin/dashboard");
                } else {
                    return redirect("/admin/login")->with("fail", "Wrong credentials!");
                }
            } else {
                return redirect("/admin/login")->with("fail", "Wrong credentials!");
            }
        } else {
            return redirect("/admin/login");
        }

        
        // if($user->account_type)
    }

    public function authenticate(Request $req){
        $validated=$req->validate([
            'studentID'=>'required',
            'password'=>'required'
        ]);

        if(auth()->attempt($validated)){
            $req->session()->regenerate();
            return redirect("/");
        } else {
            return redirect("/login");
        }
    }

    public function showProfile(){
        $data = Auth::user();
        $notifications=DB::table('notifications')->where('isSeenStudent', '=', 0)->get();
        return view("user.profile", ['user'=>$data, 'notifications'=>$notifications]);
    }

    public function logout(Request $req){
        auth()->logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect("/login");
    }

    public function createStudent(){
        $departments=Department::all();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.createStudent", ['departments'=>$departments, 'notifications'=>$notifications]);
    }

    public function addStudent(Request $req){
        $validated=$req->validate([
            "name" => "required",
            "email" => "required",
            "studentID" => "required",
            "firstName" => "required",
            "middleName" => "required",
            "lastName" => "required",
            "address" => "required",
            "gender" => "required",
            "contactNumber" => "required",
            "courseAndYear" => "required",
            "department" => "required",
            "password" => "required",
       ]);
       $validated['password']=Hash::make($validated['password']);
       $user=User::create($validated);

       return redirect("/admin/create-student")->with("registered", "Student added!");
    }

    public function createAccount(){
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.createAdminAccount", ['notifications'=>$notifications]);
    }

    public function addStaff(Request $req){
        $validated=$req->validate([
            "name"=>"required",
            "password"=>"required",
            "account_type"=>"required",
            "firstName"=>"required",
            "middleName"=>"required",
            "lastName"=>"required",
        ]);
        $validated['password']=Hash::make($validated['password']);
        $user=User::create($validated);

        return redirect("/admin/dashboard ");
    }

    public function addDepartment(){
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.addDepartment", ['notifications'=>$notifications]);
    }

    public function postDepartment(Request $req){
        $validated=$req->validate([
            "department"=>"required"
        ]);
        $department=Department::create($validated);

        return redirect("/admin/departments")->with("saved", "Department saved!");
    }

    public function borrowBook($id){
        $data = Auth::user();
        $book=Book::findOrFail($id);
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("user.borrow", ['book'=>$book, 'user'=>$data, 'notifications'=>$notifications]);
    }

    public function createTransaction(Request $req){
        $date=now()->format('Y-m-d');
        $dueDate=now()->addDay()->format('Y-m-d');
        $req['dateBorrowed']=$date;
        $req['dueDate']=$dueDate;
        $validated=$req->validate([
            "isbn" => "nullable",
            "issn" => "nullable",
            "title" => "required",
            "categories" => "required",
            "borrowerID" => "required",
            "borrower" => "required",
            "dateBorrowed" => "required",
            "dueDate" => "required",
            "status" => "required",
        ]);
        $validated['purpose'] = "1 day";

        
        $statusBook=DB::table('books')
        ->where('title', '=', $req->title)->first();

        if($statusBook->status == 'Unavailable'){
            return redirect("/borrow/$statusBook->id")->with('unavailable', 'Book is unavailable.');
        }else {
            $contentType = "none";

            if($req->isbn == 'none' && $req->issn != 'none'){
                $contentType = "Book";
            }else if($req->isbn != 'none' && $req->issn == 'none'){
                $contentType = "Journal";
            } else {
                $contentType = "Thesis";
            }

            $student = DB::table('users')->where('studentID', '=', $req->borrowerID)->first();

            
            Notification::create([
                'studentID' => $req->borrowerID,
                'borrowerName' => $student->firstName,
                'borrowedBookTitle' => $req->title,
                'borrowedBookID' => $statusBook->id,
                'borrowedContent' => $contentType,
                'isSeen' => 0,
                'isSeenStudent' => 0,
            ]);
            $book=DB::table('books')
            ->where('title', '=', $req->title)
            ->update(['status'=>'Unavailable']);
            $transaction = Transaction::create($validated);
            return redirect("/");
        }
        

    }

    public function dashboard(){
        // $books=Book::all();
        // $users = DB::table('users')
        // ->where('account_type', '=', 'student')
        // ->get();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();

        // dd($books);
        return view("admin.dashboard", ["books"=>$books, "notifications"=>$notifications]);
    }


    public function showBorrowers(){
        $users = DB::table('users')
        ->where('account_type', '=', 'student')
        ->get();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.borrowers", ["students"=>$users, 'notifications'=>$notifications]);
    }

    // public function showListBorrowers(){
    //     $users = DB::table('users')
    //     ->where('account_type', '=', 'student')
    //     ->get();
    //     $books=DB::table('books')->where('status', '=', 'Available')->get();
    //     $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
    //     return view("admin.list-borrowers", ["students"=>$users, 'notifications'=>$notifications]);
    // }

    public function showRequestedList(){
        $requested = DB::table('transactions')
        ->where('status', '=', 'pending')
        ->orWhere('status', '=', 'claimed')
        ->get();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.requested", ["transactions"=>$requested, 'notifications'=>$notifications]);
    }

    public function showReturnedList(){
        $requested = DB::table('transactions')
        ->where('status', '=', 'returned')
        ->get();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.returned", ["transactions"=>$requested, 'notifications'=>$notifications]);
    }

    public function deleteStudent($id){
        $delete = DB::table('users')->where('id', $id)->delete();
        return redirect("/admin/borrowers")->with("deleted", "Account deleted!");
    }

    public function showAdminProfile($id){
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        $user=DB::table('users')->where('id', '=', $id)->first();
        return view("admin.profile", ["user"=>$user, 'notifications'=>$notifications]);
    }

    public function updateStudent(Request $req){
        // dd($req);
        $validated=$req->validate([
            'id'=>'required',
            'studentID'=>'required',
            'firstName'=>'required',
            'middleName'=>'required',
            'lastName'=>'required',
            'address'=>'required',
            'gender'=>'required',
            'contactNumber'=>'required',
            'courseAndYear'=>'required',
            'department'=>'required',
        ]);

        $user=User::find($req->id);
        $user->studentID = $req->studentID;
        $user->firstName = $req->firstName;
        $user->middleName = $req->middleName;
        $user->lastName = $req->lastName;
        $user->address = $req->address;
        $user->gender = $req->gender;
        $user->contactNumber = $req->contactNumber;
        $user->courseAndYear = $req->courseAndYear;
        $user->department = $req->department;
        
        if($req->password != null){

            $req['password']=Hash::male($req['password']);
            $user->password = $req->password;
        }

        $user->save();
        return redirect('/admin/profile/' . $req->id)->with("updated", "Profile updated!");

    }
    
    public function showListTransaction(){
        $transactions=Transaction::all();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.list-transaction", ['transactions'=>$transactions, 'notifications'=>$notifications]);
    }

    public function filterListTransaction(Request $req){

        if(empty($req['start']) && empty($req['end']) && $req['status'] == 'none'){
            
            $transactions=Transaction::all();
            $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
            return view("admin.list-transaction", ['transactions'=>$transactions, 'notifications'=>$notifications]);

        } else if(empty($req['start']) && empty($req['end']) && $req['status'] != 'none') {

            $transactions=DB::table('transactions')->where('status', '=', $req['status'])->get();
            $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
            return view("admin.list-transaction", ['transactions'=>$transactions, 'notifications'=>$notifications]);

        } else if($req['start'] && $req['end'] && $req['status'] == 'none'){
            $req['start'] = Carbon::createFromFormat('m/d/Y', $req->start)->format('Y-m-d');
            $req['end'] = Carbon::createFromFormat('m/d/Y', $req->end)->format('Y-m-d');
            $transactions=DB::table('transactions')->whereBetween('dateBorrowed', [$req['start'], $req['end']])->get();
            $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
            return view("admin.list-transaction", ['transactions'=>$transactions, 'notifications'=>$notifications]);

        } else {
            $req['start'] = Carbon::createFromFormat('m/d/Y', $req->start)->format('Y-m-d');
            $req['end'] = Carbon::createFromFormat('m/d/Y', $req->end)->format('Y-m-d');
            $transactions=DB::table('transactions')->whereBetween('dateBorrowed', [$req['start'], $req['end']])->get();
            $transactions = $transactions->where('status', '=', $req['status']);
            $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
            return view("admin.list-transaction", ['transactions'=>$transactions, 'notifications'=>$notifications]);
        }

    }

    public function searchListTransaction(Request $req){
        $validated=$req->validate([
            'search'=>'required'
        ]);
        $transactions=DB::table('transactions')->where('title', '=', $req['search'])->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.list-transaction", ['transactions'=>$transactions, 'notifications'=>$notifications]);

        

    }

    public function exportCsv(){
    $headers = [
        'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
    ,   'Content-type'        => 'text/csv'
    ,   'Content-Disposition' => 'attachment; filename=Users.csv'
    ,   'Expires'             => '0'
    ,   'Pragma'              => 'public'
    ];

    $list = User::all()->toArray();

    # add headers for each column in the CSV download
    array_unshift($list, array_keys($list[0]));

    $callback = function() use ($list) 
    {
        $FH = fopen('php://output', 'w');
        foreach ($list as $row) { 
            fputcsv($FH, $row);
        }
        fclose($FH);
    };

    return response()->stream($callback, 200, $headers);
    
    }
    
    public function exportAvailableCsv(){
        
        $list=DB::table('books')->where('status', '=', 'Available')->get()->toArray();

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
        ,   'Content-type'        => 'text/csv'
        ,   'Content-Disposition' => 'attachment; filename=Users.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
        ];

        $list = json_decode(json_encode($list), true);
        // $list = User::all()->toArray();
        // dd($list);
    
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));
    
        $callback = function() use ($list) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) { 
                fputcsv($FH, $row);
            }
            fclose($FH);
        };
    
        return response()->stream($callback, 200, $headers);
    }

    public function exportAvailablePdf(){

        $user = DB::table('books')->where('status', '=', 'Available')->get();
        $pdf = PDF::loadView('admin.pdf',compact('user'));
            return $pdf->download('pdfview.pdf');
    }

    public function showStaffList(){
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        $users=DB::table('users')->where('account_type', '=', 'admin')->orWhere('account_type', '=', "staff")->get();
        return view("admin.staff-list", ['notifications'=>$notifications, 'users'=>$users])->with('link', 'books');
    }

    public function showStaffProfile($id){
        $staffs=User::findOrFail($id);
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.staff-profile", ['notifications'=>$notifications, 'staff'=>$staffs])->with('link', 'books');
        
    }

    public function updateStaff(Request $req){

        $validated=$req->validate([
            'firstName'=>'required',
            'middleName'=>'required',
            'lastName'=>'required',
            'name'=>'required',
            'account_type'=>'required',
        ]);

        $user = User::find($req->id);
        if($req->password != null){
            $validated['password']=Hash::make($req['password']);
            $user->password = $validated['password'];
        }
        $user->firstName = $validated['firstName'];
        $user->middleName = $validated['middleName'];
        $user->lastName = $validated['lastName'];
        $user->name = $validated['name'];
        $user->account_type = $validated['account_type'];
        $user->save();

        return redirect("/admin/staff/profile/" . $req->id)->with("saved", "Profile updated!");
    }

    public function searchAvailableBooks(Request $req){
        
        $validated=$req->validate([
            'search'=>'required'
        ]);
        $book=DB::table('books')->where('status', '=', 'Available')->get();
        $books=$book->where('title', '=', $req['search']);
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.dashboard", ['books'=>$books, 'notifications'=>$notifications]);
    }

    public function searchRequestedList(Request $req){
        
        $validated=$req->validate([
            'search'=>'required'
        ]);
        
        $request = DB::table('transactions')
        ->where('status', '=', 'pending')
        ->orWhere('status', '=', 'claimed')
        ->get();

        $requested=$request->where('title', '=', $req['search']);
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.requested", ['transactions'=>$requested, 'notifications'=>$notifications]);
    }
    public function searchReturnedList(Request $req){
        
        $validated=$req->validate([
            'search'=>'required'
        ]);
        
        $request = DB::table('transactions')
        ->where('status', '=', 'returned')
        ->get();
        
        $requested=$request->where('title', '=', $req['search']);
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.returned", ['transactions'=>$requested, 'notifications'=>$notifications]);
    }

    public function searchBorrowerList(Request $req){
        $validated=$req->validate([
            'search'=>'required'
        ]);

        $request = DB::table('users')
        ->where('account_type', '=', 'student')->get();
        
        $requested=$request->where('firstName', '=', $req['search']);
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        // $users=DB::table('users')->where('account_type', '=', 'student')->get();
        // $users=$users->where('firstName', '=', $req['search'])->orWhere('middleName', '=', $req['search'])->orWhere('lastName', '=', $req['search']);
        return view("admin.borrowers", ['notifications'=>$notifications, 'students'=>$requested]);
    }

    public function searchStaffList(Request $req){
        
        $validated=$req->validate([
            'search'=>'required'
        ]);

        

        $users = DB::table('users')
        ->where('account_type', '=', 'admin')->orWhere('account_type', '=', 'staff')->get();
        
        $users=$users->where('name', '=', $req['search']);
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        // $users=DB::table('users')->where('account_type', '=', 'student')->get();
        // $users=$users->where('firstName', '=', $req['search'])->orWhere('middleName', '=', $req['search'])->orWhere('lastName', '=', $req['search']);
        return view("admin.staff-list", ['notifications'=>$notifications, 'users'=>$users])->with('link', 'books');
    }

    public function showUserTransactions($id){
        $user=User::findOrFail($id);
        $transactions = DB::table('transactions')->where('borrowerID', '=', $user->studentID)->get();
        $notifications=DB::table('notifications')->where('isSeenStudent', '=', 0)->get();
        return view("user.transaction-history", ['transactions'=>$transactions, 'notifications'=>$notifications]);
    }
    
}

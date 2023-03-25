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

class UserController extends Controller
{
    public function home(){
        return view("welcome");
    }

    public function login(){
        return view("user.login");
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
        return view("user.profile", ['user'=>$data]);
    }

    public function logout(Request $req){
        auth()->logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect("/login");
    }

    public function createStudent(){
        $departments=Department::all();
        return view("admin.createStudent", ['departments'=>$departments]);
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
        return view("admin.createAdminAccount");
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

        return redirect("/admin ");
    }

    public function addDepartment(){
        return view("admin.addDepartment");
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
        return view("user.borrow", ['book'=>$book, 'user'=>$data]);
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

        $statusBook=DB::table('books')
        ->where('title', '=', $req->title)->first();

        if($statusBook->status == 'Unavailable'){
            return redirect("/borrow/$statusBook->id")->with('unavailable', 'Book is unavailable.');
        }else {
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
        return view("admin.dashboard", ["books"=>$books]);
    }

    public function showBorrowers(){
        $users = DB::table('users')
        ->where('account_type', '=', 'student')
        ->get();
        return view("admin.borrowers", ["students"=>$users]);
    }

    public function showRequestedList(){
        $requested = DB::table('transactions')
        ->where('status', '=', 'pending')
        ->orWhere('status', '=', 'claimed')
        ->get();
        return view("admin.requested", ["transactions"=>$requested]);
    }

    public function showReturnedList(){
        $requested = DB::table('transactions')
        ->where('status', '=', 'returned')
        ->get();
        return view("admin.returned", ["transactions"=>$requested]);
    }

    public function addData(){
        return view("admin.add-data");
    }

    public function deleteStudent($id){
        $delete = DB::table('users')->where('id', $id)->delete();
        return redirect("/admin/borrowers")->with("deleted", "Account deleted!");
    }


    
}

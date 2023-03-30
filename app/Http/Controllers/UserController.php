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
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Response;
use \PDF;
use \JavaScript;

class UserController extends Controller
{
    public function home(){
        $notifications=DB::table('notifications')->where('isSeenStudent', '=', 0)->get();
        $categories = Category::all();
        return view("welcome", ['notifications'=>$notifications, 'categories'=>$categories]);
    }

    public function login(){
        return view("user.login");
    }

    public function adminLogin(){
        return view("admin.login");
    }

    public function adminAuthenticate(Request $req){
        // dd($req);
        $validated=$req->validate([
            'name'=>'required',
            'password'=>'required',
        ]);
        // dd($validated);

        if(auth()->attempt($validated)){
            $user=DB::table('users')->where('name', '=', $req->name)->first();
            if($user != null){
                if($user->account_type == 'admin' || $user->account_type == 'librarian'){
                    $req->session()->regenerate();
                    return redirect("/admin/dashboard");
                } else {
                    return redirect("/admin/login")->with("fail", "Wrong credentials!");
                }
            } else {
                return redirect("/admin/login")->with("fail", "Wrong credentials!");
            }
        } else {
            return redirect("/admin/login")->with("fail", "You do not have access here!");
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
            return redirect("/login")->with("fail", "Wrong credentials!");
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
       $validated['name']="none";
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
        $departments=Department::all();
        return view("user.borrow", ['book'=>$book, 'user'=>$data, 'notifications'=>$notifications, 'departments'=>$departments]);
    }

    public function createTransaction(Request $req){
        $date=now()->format('Y-m-d');
        $dueDate=now()->addDay()->format('Y-m-d');
        $req['dateBorrowed']=$date;
        $req['dueDate']=$dueDate;
        $validated=$req->validate([
            "isbn" => "nullable",
            "issn" => "nullable",
            "bookID"=>"required",
            "title" => "required",
            "categories" => "required",
            "borrowerID" => "required",
            "borrower" => "required",
            'borrowerAddress' => 'required',
            'borrowerContactNumber' => 'required',
            'borrowerCourseAndYear' => 'required',
            'borrowerDepartment' => 'required',
            'librarian' => 'required',
            'bookPrice'=>'required',
            "dateBorrowed" => "required",
            "dueDate" => "required",
            "status" => "required",
        ]);

        $bookStatus = Book::find($validated['bookID']);
        $bookQuantity = DB::table('books')->where('id', '=', $validated['bookID'])->decrement('quantity');
        $quantity = $bookStatus->quantity;
        if($quantity < 0){
            $bookStatus->status = 'Unavailable';
        }

        $bookStatus->save();
        
        $validated['purpose'] = "1 day";

        // dd($validated);
        
        $statusBook=DB::table('books')
        ->where('title', '=', $req->title)->first();

        if($statusBook->status == 'Unavailable'){
            return redirect("/borrow/$statusBook->id")->with('unavailable', 'Book is unavailable.');
        }else {
            $contentType = "none";

            if($req->isbn != 'none' && $req->issn == 'none'){
                $contentType = "Book";
            }else if($req->isbn == 'none' && $req->issn != 'none'){
                $contentType = "Journal";
            } else {
                $contentType = "Thesis";
            }

            $student = DB::table('users')->where('studentID', '=', $req->borrowerID)->first();

            $name = $validated['borrower'];

            if($student != null){
                
            if($student->firstName == null){
                $name = $validated['borrower'];
    
                } else {
                    $name = $student->firstName;
                }
            }

            $walkIn = DB::table('users')->where('studentID', '=', $validated['borrowerID'])->get();

            if(count($walkIn) == 0){
                $validated['status'] = 'claimed';
                $book=DB::table('books')
                ->where('title', '=', $req->title)
                ->update(['status'=>'Unavailable']);
                $transaction = Transaction::create($validated);
                return redirect("/");
            } else {
                Notification::create([
                    'studentID' => $req->borrowerID,
                    'borrowerName' => $name,
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
        

    }

    public function dashboard(){
        // $books=Book::all();
        // $users = DB::table('users')
        // ->where('account_type', '=', 'student')
        // ->get();

        


        $overallSHSUsers = DB::table('users')->where('department', '=', 'SHS')->select('studentID')->distinct();
        $overallSHSTransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'SHS')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallSHS = $overallSHSUsers->union($overallSHSTransaction)->get();
        $shsBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'SHS')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $shsPercentage;
        if(count($overallSHS) != 0){
            $shsPercentage = count($shsBorrower) / count($overallSHS) * 100;
        } else {
            $shsPercentage = 0;
        }
        
        

        $overallCASUsers = DB::table('users')->where('department', '=', 'CAS')->select('studentID')->distinct();
        $overallCASTransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CAS')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallCAS = $overallCASUsers->union($overallCASTransaction)->get();
        $casBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CAS')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $casPercentage;
        if(count($overallCAS) != 0){
            $casPercentage = count($casBorrower) / count($overallCAS) * 100;
        } else {
            $casPercentage = 0;
        }

        $overallCEAUsers = DB::table('users')->where('department', '=', 'CEA')->select('studentID')->distinct();
        $overallCEATransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CEA')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overAllCEA = $overallCEAUsers->union($overallCEATransaction)->get();
        $ceaBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CEA')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        // dd($ceaBorrower);

        $ceaPercentage;
        if(count($overAllCEA) != 0){
            $ceaPercentage = count($ceaBorrower) / count($overAllCEA) * 100;
        } else {
            $ceaPercentage = 0;
        }
        // dd($overAllCEA);
        $overallCMAUsers = DB::table('users')->where('department', '=', 'CMA')->select('studentID')->distinct();
        $overallCMATransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CMA')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallCMA = $overallCMAUsers->union($overallCMATransaction)->get();
        $cmaBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CMA')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $cmaPercentage;
        if(count($overallCMA) != 0){
            $cmaPercentage = count($cmaBorrower) / count($overallCMA) * 100;
        } else {
            $cmaPercentage = 0;
        }

        $overallCELAUsers = DB::table('users')->where('department', '=', 'CELA')->select('studentID')->distinct();
        $overallCELATransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CELA')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallCELA = $overallCELAUsers->union($overallCELATransaction)->get();
        $celaBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CELA')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $celaPercentage;
        if(count($overallCELA) != 0){
            $celaPercentage = count($celaBorrower) / count($overallCELA) * 100;
        } else {
            $celaPercentage = 0;
        }
        $overallCHSUsers = DB::table('users')->where('department', '=', 'CHS')->select('studentID')->distinct();
        $overallCHSTransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CHS')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallCHS = $overallCHSUsers->union($overallCHSTransaction)->get();
        $chsBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CHS')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $chsPercentage;
        if(count($overallCHS) != 0){
            $chsPercentage = count($chsBorrower) / count($overallCHS) * 100;
        } else {
            $chsPercentage = 0;
        }
        $overallCITEUsers = DB::table('users')->where('department', '=', 'CITE')->select('studentID')->distinct();
        $overallCITETransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CITE')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallCITE = $overallCITEUsers->union($overallCITETransaction)->get();
        $citeBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CITE')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $citePercentage;
        if(count($overallCITE) != 0){
            $citePercentage = count($citeBorrower) / count($overallCITE) * 100;
        } else {
            $citePercentage = 0;
        }
        $overallCCJEUsers = DB::table('users')->where('department', '=', 'CCJE')->select('studentID')->distinct();
        $overallCCJETransaction = DB::table('transactions')->where('borrowerDepartment', '=', 'CCJE')->where('remarks', '=', 'ongoing')->select('borrowerID')->distinct();
        $overallCCJE = $overallCCJEUsers->union($overallCCJETransaction)->get();
        $ccjeBorrower = DB::table('transactions')->where('borrowerDepartment', '=', 'CCJE')->where('remarks', '=', 'ongoing')->where('status', '=', 'claimed')->select('borrowerID')->distinct()->get();

        $ccjePercentage;
        if(count($overallCCJE) != 0){
            $ccjePercentage = count($ccjeBorrower) / count($overallCCJE) * 100;
        } else {
            $ccjePercentage = 0;
        }
        
        

        $overallUsers = DB::table('users')
        ->where('account_type', '=', 'student')->get();
        $borrowerUsers = DB::table('transactions')->where('remarks', '=', 'ongoing')->distinct()->get(); 


        $userPercentage;
        if(count($overallUsers) != 0){ 
            $userPercentage = count($borrowerUsers) / count($overallUsers) * 100;
        } else {
            $userPercentage = 0;
        }
        
        // $borrowedBooks=DB::table('books')->where('status', '=', 'Unavailable')->get();
        // $borrowedBooks=DB::table('transactions')->where('status', '=', 'claimed')->get();
        // $borrowedBooks = DB::table('transactions')->where('status', '=', 'claimed')->get();

        $unavailableBooks=DB::table('books')->where('status', '=', 'Unavailable')->pluck('id')->toArray();
        $borrowedBooks=DB::table('transactions')->whereIn('bookID', $unavailableBooks)->where('status', '=', 'claimed')->get();


        $overallBooks=Book::all();
        
        $booksPercentage = count($borrowedBooks) / count($overallBooks) * 100;
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();


        // dd($borrowedBooks);
        return view("admin.dashboard", [
            'books'=>$books,
            'notifications'=>$notifications,
            'borrowedBooks'=>$borrowedBooks,
            'bookPercentage' => $booksPercentage,
            'overallBooks'=>$overallBooks,
            'userPercentage'=>$userPercentage,
            'borrowerUsers'=>$borrowerUsers,
            'overallUsers'=>$overallUsers,
            'shsPercentage'=>$shsPercentage,
            'shsBorrowers'=>$shsBorrower,
            'overallSHS'=>$overallSHS,
            'casPercentage'=>$casPercentage,
            'casBorrowers'=>$casBorrower,
            'overallCAS'=>$overallCAS,
            'ceaPercentage'=>$ceaPercentage,
            'ceaBorrowers'=>$ceaBorrower,
            'overallCEA'=>$overAllCEA,
            'cmaPercentage'=>$cmaPercentage,
            'cmaBorrowers'=>$cmaBorrower,
            'overallCMA'=>$overallCMA,
            'celaPercentage'=>$celaPercentage,
            'celaBorrowers'=>$celaBorrower,
            'overallCELA'=>$overallCELA,
            'chsPercentage'=>$chsPercentage,
            'chsBorrowers'=>$chsBorrower,
            'overallCHS'=>$overallCHS,
            'citePercentage'=>$citePercentage,
            'citeBorrowers'=>$citeBorrower,
            'overallCITE'=>$overallCITE,
            'ccjePercentage'=>$ccjePercentage,
            'ccjeBorrowers'=>$ccjeBorrower,
            'overallCCJE'=>$overallCCJE,
        ]);
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
    public function showClaimedList(){
        $requested = DB::table('transactions')
        ->where('status', '=', 'claimed')
        ->get();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.claimed", ["transactions"=>$requested, 'notifications'=>$notifications]);
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
        $transactions=DB::table('transactions')->where('borrower', '=', $req['search'])->get();
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
        $users=DB::table('users')->where('account_type', '=', 'admin')->orWhere('account_type', '=', "librarian")->get();
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

        // dd($req->profileImage);

        $user = User::find($req->id);
        if($req->password != null){
            $validated['password']=Hash::make($req['password']);
            $user->password = $validated['password'];
        }
        
        if($req->profileImage != null){
            $imageName = time().'.'.$req->profileImage->extension();  
 
            $req->profileImage->move(public_path('users'), $imageName);     
            $user->profileImage = '/'.'users/' . $imageName;
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
    
    public function viewBookBorrowed($id){
        $book=Book::findOrFail($id);
        $transaction=DB::table('transactions')->where('bookID', $id)->first();
        $notifications=DB::table('notifications')->where('isSeenStudent', '=', 0)->get();
        return view("user.view-book", ['book'=>$book, 'notifications'=>$notifications, 'transaction'=>$transaction]);
    }

    public function updateStudentProfile(Request $req){

        

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
        // dd($validated);



        $student = User::find($req->id);

        if($req->password != null){
            $validated['password']=Hash::make($req['password']);
            $student->password = $validated['password'];
        }
        if($req->profileImage != null){
            $imageName = time().'.'.$req->profileImage->extension();  
 
            $req->profileImage->move(public_path('users'), $imageName);     
            $student->profileImage = '/'.'users/' . $imageName;
        }

        $student->studentID = $validated['studentID'];
        $student->firstName = $validated['firstName'];
        $student->middleName = $validated['middleName'];
        $student->lastName = $validated['lastName'];
        $student->address = $validated['address'];
        $student->gender = $validated['gender'];
        $student->contactNumber = $validated['contactNumber'];
        $student->courseAndYear = $validated['courseAndYear'];
        $student->department = $validated['department'];

        $student->save();

        return redirect("/profile")->with("saved", "Profile updated!");


    }

    public function booksAndBorrowersMonthly(){
        // $books = Transaction::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
        //     ->groupBy('month')
        //     ->get();

        // $booksMonthly = $books->pluck('month')->toJson();

        // $books = Transaction::all(); //all books
        // $borrower = DB::table('transactions')->select('borrowerID')->distinct()->get(); //all unique borrowers
        // dd($booksMonthly);
        $borrower = DB::table('transactions')
    ->select('borrowerID')
    ->whereMonth('created_at', date('m'))
    ->whereYear('created_at', date('Y'))
    ->distinct()
    ->get();

    dd(count($borrower));

        $borrowedBooks = DB::table('transactions')
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->get()->toArray();
        dd($borrowedBooks);
    }

    public function departmentMonthly(){
        $shs = DB::table('transactions')->where('borrowerDepartment', '=', 'SHS')->get();
        $cas = DB::table('transactions')->where('borrowerDepartment', '=', 'CAS')->get();
        $cea = DB::table('transactions')->where('borrowerDepartment', '=', 'CEA')->get();
        $cma = DB::table('transactions')->where('borrowerDepartment', '=', 'CMA')->get();
        $cela = DB::table('transactions')->where('borrowerDepartment', '=', 'CELA')->get();
        $chs = DB::table('transactions')->where('borrowerDepartment', '=', 'CHS')->get();
        // $cite = DB::table('transactions')->where('borrowerDepartment', '=', 'CITE')->get();
        $ccje = DB::table('transactions')->where('borrowerDepartment', '=', 'CCJE')->get();

        $cite = Transaction::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->groupBy('month')
            ->get();

        $citeMonth = $cite->pluck('month')->toJson(); //[3, 4]
        $citeUsers = $cite->pluck('total')->toJson(); //[2, 1]
        dd($citeUsers);
    }
    public function booksBorrowersGraph() {
        
        $months = DB::table('transactions')
        ->selectRaw('DISTINCT MONTH(created_at) as month')
        ->orderBy('month')
        ->pluck('month');
        
        $borrower = [];
        for ($month = 0; $month <= (count($months)-1); $month++) {
            $count = DB::table('transactions')
            ->select('borrowerID')
            ->whereMonth('created_at', $months[$month])
            ->whereYear('created_at', date('Y'))
            ->distinct()
            ->get();
            $borrower[$month] = count($count);
        }

        $borrowedBooks = DB::table('transactions')
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->get()->toArray();

        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();

        $borrower=json_encode($borrower);
        $borrowedBooks=json_encode($borrowedBooks);

        JavaScript::put([
            'borrower' => $borrower,
            'books' => $borrowedBooks
        ]);

        return view("admin.booksBorrowersGraph", ["books"=>$books, "notifications"=>$notifications,]);
    }

    public function departmentGraph() {
        $months = DB::table('transactions')
        ->selectRaw('DISTINCT MONTH(created_at) as month')
        ->orderBy('month')
        ->pluck('month');

        $departments = array("SHS", "CAS", "CEA", "CMA", "CELA", "CHS", "CITE", "CCJE");

        $userDepartment = [];
        for ($dep = 0; $dep < count($months); $dep++) {
            $userDepartmentMonth = [];
            for ($dep1 = 0; $dep1 < count($departments); $dep1++) {
                $user = DB::table('transactions')
                ->select('borrowerID')
                ->where('borrowerDepartment', '=', $departments[$dep1])
                ->whereMonth('created_at', $months[$dep])
                ->distinct()
                ->get();
                $userDepartmentMonth[$dep1] = count($user);
            }
            $userDepartment[$dep] = $userDepartmentMonth;
        }

        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();

        
        return view("admin.departmentGraph", ["books"=>$books, "notifications"=>$notifications, 'userDepartment' => json_encode($userDepartment),
        'months' => json_encode($months),]);
    }

}

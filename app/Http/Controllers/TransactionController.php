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
use DateTime;

class TransactionController extends Controller
{
    //

    public function decline($id){
        $transaction=Transaction::findOrFail($id);
        dd($transaction);
    }

    public function claimed($id){
        $transaction=Transaction::find($id);
        $transaction->status = "claimed";
        $transaction->save();
        return redirect("/admin/requested-list");
    }

    public function returned($id){
        $transaction=Transaction::find($id);
        $transaction->status = "returned";
        $returnDate = now()->format('Y-m-d');
        $transaction->dateReturned = $returnDate;

        
        $date1 = new DateTime($transaction->dueDate);
        $date2 = new DateTime($returnDate);

        $interval = $date1->diff($date2);



        if ($interval->invert == 0 || $interval->invert > 0) {
            $transaction->remarks = "ontime";

        } else {

            $transaction->remarks = "overdue";
        }
        
        $transaction->save();
        return redirect("/admin/requested-list");
    }

    public function deleteTransaction($id){
        $delete = DB::table('transactions')->where('id', $id)->delete();
        return redirect("/admin/requested-list")->with("deleted", "Transaction deleted!");
    }
}

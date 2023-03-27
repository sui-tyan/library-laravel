<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class NotificationController extends Controller
{
    //
    public function seen($id){
        $seen=Notification::findOrFail($id);
        $seen->isSeen = 1;
        $seen->save();
        return redirect("/admin/requested-list");
    }

    public function studentSeen($id){
        $seen=Notification::findOrFail($id);
        $seen->isSeenStudent = 1;
        $seen->save();
        return redirect("/view-book/".$seen->borrowedBookID);
    }
}

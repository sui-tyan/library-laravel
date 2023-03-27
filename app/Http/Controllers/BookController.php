<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class BookController extends Controller
{
    //
    
    public function books(){
        
        $category=Category::all();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("user.books", ['categories'=>$category, 'books'=>$books, 'notifications'=>$notifications]);
    }

    public function findBooks(){
        $category=Category::all();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("user.find-books", ["category"=>$category, 'notifications'=>$notifications]);
    }

    public function queryBook(Request $req){

        $validated=$req->validate([
            'title' => 'required_without_all:categories,author,publisher,publishedDate',
            'categories' => 'required_without_all:title,author,publisher,publishedDate',
            'author' => 'required_without_all:title,categories,publisher,publishedDate',
            'publisher' => 'required_without_all:title,categories,author,publishedDate',
            'publishedDate' => 'required_without_all:title,categories,author,publisher',
        ]);

        if($validated['publishedDate'] != null){
            $validated['publishedDate'] = Carbon::createFromFormat('m/d/Y', $req->publishedDate)->format('Y-m-d');
        }
        if($validated['categories'] == 'any') {

            
            $queryResult=DB::table('books')
            ->where('title', $validated['title'])
            ->orWhere('categories', $validated['categories'])
            ->orWhere('author', $validated['author'])
            ->orWhere('publisher', $validated['publisher'])
            ->orWhere('publishedDate', $validated['publishedDate'])
            ->get();
            $books=DB::table('books')->where('status', '=', 'Available')->get();
            $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
            return view("user.found-books", ['books'=>$queryResult, 'notifications'=>$notifications]);


        } else {
            
        $queryResult=DB::table('books')
        ->where('title', $validated['title'])
        ->orWhere('categories', $validated['categories'])
        ->orWhere('author', $validated['author'])
        ->orWhere('publisher', $validated['publisher'])
        ->orWhere('publishedDate', $validated['publishedDate'])
        ->get();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("user.found-books", ['books'=>$queryResult, 'notifications'=>$notifications]);
        }


        

        
    }

    public function addBook(){
        $category=Category::all();
        
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.books", ['category'=>$category, 'notifications'=>$notifications])->with('link', 'book');
    }
    public function addJournal(){
        $category=Category::all();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.books", ['category'=>$category, 'notifications'=>$notifications])->with('link', 'journal');
    }
    public function addThesis(){
        $category=Category::all();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.books", ['category'=>$category, 'notifications'=>$notifications])->with('link', 'thesis');
    }

    public function postBook(Request $req){
        $validated=$req->validate([
            "isbn"=>"required",
            "title"=>"required",
            "description"=>"required",
            "author"=>"required",
            "publisher"=>"required",
            "price"=>"required",
            "publishedDate"=>"required",
            "categories"=>"required",
            "type"=>"required",
            "remarks"=>"required",
        ]);

        $validated['publishedDate'] = Carbon::createFromFormat('m/d/Y', $req->publishedDate)->format('Y-m-d');
        $query=DB::table("categories")->where('category', $validated['categories'])->get();
        $deweyDecimal = $query->pluck('deweyDecimal')->first();
        $validated['deweyDecimal'] = $deweyDecimal;


        $book=Book::create($validated);

        return redirect("/admin/books")->with("saved", "Book saved!");

    }

    public function postJournal(Request $req){
        $validated=$req->validate([
            "issn"=>"required",
            "title"=>"required",
            "description"=>"required",
            "author"=>"required",
            "publisher"=>"required",
            "price"=>"required",
            "publishedDate"=>"required",
            "categories"=>"required",
            "type"=>"required",
            "remarks"=>"required",
        ]);

        $validated['publishedDate'] = Carbon::createFromFormat('m/d/Y', $req->publishedDate)->format('Y-m-d');
        $query=DB::table("categories")->where('category', $validated['categories'])->get();
        $deweyDecimal = $query->pluck('deweyDecimal')->first();
        $validated['deweyDecimal'] = $deweyDecimal;


        $book=Book::create($validated);

        return redirect("/admin/journals")->with("saved", "Journal saved!");

    }

    public function postThesis(Request $req){
        $validated=$req->validate([
            "title"=>"required",
            "description"=>"required",
            "author"=>"required",
            "publisher"=>"required",
            "price"=>"required",
            "publishedDate"=>"required",
            "categories"=>"required",
            "type"=>"required",
            "remarks"=>"required",
        ]);

        $validated['publishedDate'] = Carbon::createFromFormat('m/d/Y', $req->publishedDate)->format('Y-m-d');
        $query=DB::table("categories")->where('category', $validated['categories'])->get();
        $deweyDecimal = $query->pluck('deweyDecimal')->first();
        $validated['deweyDecimal'] = $deweyDecimal;


        $book=Book::create($validated);

        return redirect("/admin/thesis")->with("saved", "Thesis saved!");

    }

    public function addCategory(){
        $category=Category::all();
        
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.category", ['category'=>$category, 'notifications'=>$notifications]);
    }

    public function postCategory(Request $req){
        // dd($req);
        $validated=$req->validate([
            "category"=>"required",
        ]);
        
       $validated['deweyDecimal']="0";

        $category=Category::create($validated);
        
        return redirect("/admin/category")->with("saved", "Category saved!");
    }

    public function showBook($id){
        $book=Book::findOrFail($id);
        $categories=Category::all();
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("user.show-book", ['book'=>$book, 'categories'=>$categories, 'notifications'=>$notifications]);
    }

    public function showCategories($id){
        $categories=Category::all();
        $selectedCategory=Category::findOrFail($id);
        $showBooks=Book::where('categories', $selectedCategory->category)->get();
        
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("user.show-categories", ['showBooks'=>$showBooks, 'showCategories'=>$categories, 'notifications'=>$notifications]);
    }

    public function deleteBook($id){
        $delete = DB::table('books')->where('id', $id)->delete();
        return redirect("/admin/book-list")->with("deleted", "Book deleted!");
    }

    public function editBook($id){
        $edit=Book::findOrFail($id);
        $category=Category::all();
        
        $books=DB::table('books')->where('status', '=', 'Available')->get();
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        if($edit->isbn == 'none' && $edit->issn == 'none'){

            return view("admin.edit-book", ["book"=>$edit, 'category'=>$category, 'notifications'=>$notifications])->with('link', 'thesis');

        } else if($edit->isbn != 'none' && $edit->issn == 'none'){

            return view("admin.edit-book", ["book"=>$edit, 'category'=>$category, 'notifications'=>$notifications])->with('link', 'book');

        } else if($edit->isbn == 'none' && $edit->issn != 'none'){

            return view("admin.edit-book", ["book"=>$edit, 'category'=>$category, 'notifications'=>$notifications])->with('link', 'journal');

        }
    }
    
    public function updateBook(Request $req){
        $validated=$req->validate([
            "isbn"=>"required",
            "title"=>"required",
            "description"=>"required",
            "author"=>"required",
            "publisher"=>"required",
            "price"=>"required",
            "publishedDate"=>"required",
            "categories"=>"required",
            "type"=>"required",
            "remarks"=>"required",
        ]);

        $validated['publishedDate'] = Carbon::createFromFormat('m/d/Y', $req->publishedDate)->format('Y-m-d');
        $query=DB::table("categories")->where('category', $validated['categories'])->get();
        $deweyDecimal = $query->pluck('deweyDecimal')->first();
        $validated['deweyDecimal'] = $deweyDecimal;
        
        

        $book=Book::find($req->id);
        if($book->remarks == 'Lost'){
            $book->status = "Unavailable";
        } else if($book->remarks == "Good") {
            $book->status = "Available";
        }
        $book->isbn = $validated['isbn'];
        $book->title = $validated['title'];
        $book->description = $validated['description'];
        $book->author = $validated['author'];
        $book->publisher = $validated['publisher'];
        $book->price = $validated['price'];
        $book->publishedDate = $validated['publishedDate'];
        $book->categories = $validated['categories'];
        $book->type = $validated['type'];
        $book->remarks = $validated['remarks'];
        $book->save();

        return redirect("/admin/book-list")->with("saved", "Book saved!");
    }
    
    public function showBookList(){
        $books=DB::table('books')
        ->where('isbn', '!=', 'none')
        ->where('issn', '=', 'none')
        ->get();

        // $books=$books->where('status', '=', 'Available')->orWhere('status', '=', 'Unavailable')->get();

        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.book-list", ["books"=>$books, "notifications"=>$notifications])->with('success', 'Books')->with('link', 'books');
    }

    public function showJournalList(){
        $books=DB::table('books')
        ->where('issn', '!=', 'none')
        ->where('isbn', '=', 'none')
        ->get();
        $books=$books->where('status', '=', 'Available');
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.book-list", ["books"=>$books, 'notifications'=>$notifications])->with('success', 'Journals')->with('link', 'journals');
    }

    public function showThesisList(){
        $books=DB::table('books')
        ->where('issn', '=', 'none')
        ->where('isbn', '=', 'none')
        ->get();
        $books=$books->where('status', '=', 'Available');
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.book-list", ["books"=>$books, 'notifications'=>$notifications])->with('success', 'Thesis')->with('link', 'thesis');
    }
    
    public function searchBooksList(Request $req){

        $validated=$req->validate([
            'search'=>'required'
        ]);

        $books=DB::table('books')
        ->where('isbn', '!=', 'none')
        ->where('issn', '=', 'none')
        ->get();
        $books=$books->where('status', '=', 'Available');
        $books=$books->where('title', '=', $req['search']);
        
        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.book-list", ["books"=>$books, "notifications"=>$notifications])->with('success', 'Books')->with('link', 'books');
    }

    public function searchJournalsList(Request $req){
        
        $validated=$req->validate([
            'search'=>'required'
        ]);

        $books=DB::table('books')
        ->where('isbn', '=', 'none')
        ->where('issn', '!=', 'none')
        ->get();
        $books=$books->where('status', '=', 'Available');
        $books=$books->where('title', '=', $req['search']);

        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.book-list", ["books"=>$books, 'notifications'=>$notifications])->with('success', 'Journals')->with('link', 'journals');

    }
    public function searchThesisList(Request $req){

        $validated=$req->validate([
            'search'=>'required'
        ]);

        $books=DB::table('books')
        ->where('isbn', '=', 'none')
        ->where('issn', '!=', 'none')
        ->get();
        $books=$books->where('status', '=', 'Available');
        $books=$books->where('title', '=', $req['search']);

        $notifications=DB::table('notifications')->where('isSeen', '=', 0)->get();
        return view("admin.book-list", ["books"=>$books, 'notifications'=>$notifications])->with('success', 'Thesis')->with('link', 'thesis');
    }
}

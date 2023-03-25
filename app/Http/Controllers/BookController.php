<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    //
    
    public function books(){
        return view("user.books");
    }

    public function findBooks(){
        $category=Category::all();
        return view("user.find-books", ["category"=>$category]);
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

        $queryResult=DB::table('books')
        ->where('title', $validated['title'])
        ->orWhere('categories', $validated['categories'])
        ->orWhere('author', $validated['author'])
        ->orWhere('publisher', $validated['publisher'])
        ->orWhere('publishedDate', $validated['publishedDate'])
        ->get();
        
        return view("user.found-books", ['books'=>$queryResult]);

        
    }

    public function addBook(){
        $category=Category::all();
        return view("admin.books", ['category'=>$category]);
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

    public function addCategory(){
        $category=Category::all();
        return view("admin.category", ['category'=>$category]);
    }

    public function postCategory(Request $req){
        $validated=$req->validate([
            "category"=>"required",
            "deweyDecimal"=>"required",
        ]);

        $category=Category::create($validated);
        
        return redirect("/admin/category")->with("saved", "Category saved!");
    }

    public function showBook($id){
        $book=Book::findOrFail($id);
        $categories=Category::all();
        return view("user.show-book", ['book'=>$book, 'categories'=>$categories]);
    }

    public function showCategories($id){
        $categories=Category::all();
        $selectedCategory=Category::findOrFail($id);
        $showBooks=Book::where('categories', $selectedCategory->category)->get();
        return view("user.show-categories", ['showBooks'=>$showBooks, 'showCategories'=>$categories]);
    }

    public function deleteBook($id){
        $delete = DB::table('books')->where('id', $id)->delete();
        return redirect("/admin/book-list")->with("deleted", "Book deleted!");
    }

    public function editBook($id){
        $edit=Book::findOrFail($id);
        $category=Category::all();
        return view("admin.edit-book", ["book"=>$edit, 'category'=>$category]);
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
}

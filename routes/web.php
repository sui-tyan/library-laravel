<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/logout", [UserController::class, "logout"]);
Route::get("/", [UserController::class, "home"]);
Route::get("/books", [BookController::class, "books"]);
Route::get("/find-books", [BookController::class, "findBooks"]);
Route::post("/query-book", [BookController::class, "queryBook"]);
Route::get("/profile", [UserController::class, "showProfile"])->middleware("auth");
Route::get("/books/{id}", [BookController::class, "showBook"]);
Route::get("/category/{id}", [BookController::class, "showCategories"]);
Route::get("/borrow/{id}", [UserController::class, "borrowBook"]);
Route::post("/create-transaction", [UserController::class, "createTransaction"]);


Route::get("/login", [UserController::class, "login"])->name("login")->middleware('guest');
Route::post("/authenticate", [UserController::class, "authenticate"]);
Route::get("/admin/create-student", [UserController::class, "createStudent"]);
Route::post("/addStudent", [UserController::class, "addStudent"]);
Route::get("/admin/create-account", [UserController::class, "createAccount"]);
Route::post("/addStaff", [UserController::class, "addStaff"]);
Route::get("/admin/departments", [UserController::class, "addDepartment"]);
Route::post("/admin/add-department", [UserController::class, "postDepartment"]);

Route::get("/admin/add-data", [UserController::class, "addData"]);
Route::get("/admin/dashboard", [UserController::class, "dashboard"]);
Route::get("/admin/borrowers", [UserController::class, "showBorrowers"]);
Route::get("/admin/requested-list", [UserController::class, "showRequestedList"]);
Route::get("/admin/returned-list", [UserController::class, "showReturnedList"]);

Route::get("/admin/books", [BookController::class, "addBook"]);
Route::post("/admin/add-book", [BookController::class, "postBook"]);
Route::post("/admin/update-book", [BookController::class, "updateBook"]);

Route::get("/admin/category", [BookController::class, "addCategory"]);
Route::post("/admin/add-category", [BookController::class, "postCategory"]);

Route::get("/admin/decline/{id}", [TransactionController::class, "decline"]);
Route::get("/admin/claimed/{id}", [TransactionController::class, "claimed"]);
Route::get("/admin/returned/{id}", [TransactionController::class, "returned"]);

Route::get("/admin/delete/account/{id}", [UserController::class, "deleteStudent"]);


Route::get("/admin/book-list", [UserController::class, "showBookList"]);


Route::get("/admin/delete/transaction/{id}", [TransactionController::class, "deleteTransaction"]);
Route::get("/admin/delete/book/{id}", [BookController::class, "deleteBook"]);
Route::get("/admin/edit/book/{id}", [BookController::class, "editBook"]);
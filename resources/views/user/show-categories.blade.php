@include('partials.header')
<x-nav />
<div class="grid grid-cols-3">
    <div class="books col-span-2 mx-10">
        @foreach($showBooks as $book)
        <div class="book my-10">
            <h2 class="text-2xl font-semibold italic">{{$book->title}}</h2>
            <div class="book-details">
                <p>Publish Date: {{$book->publishedDate}}</p>
                <p>Author: {{$book->author}}</p>
                <p>Description: {{$book->description}}</p>
                <p>Category: {{$book->categories}}</p>
                <p>Price: {{$book->price}}</p>
                <p>Type: {{$book->type}} </p>
                <p>Publisher: {{$book->publisher}}</p>
                <a href="/borrow/{{$book->id}}" class="my-5 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Borrow Book
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="book-categories">
        
    <h2 class="text-1xl font-semibold italic">Book Categories</h2>
    @foreach($showCategories as $category)
        <a href="/category/{{$category->id}}" class="block">{{$category->category}}</a>
    @endforeach
    </div>
</div>
@include('partials.footer')
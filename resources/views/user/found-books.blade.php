@include('partials.header')
<x-nav />
<div class="">
    <div class="books col-span-2 mx-10 p-5">
        @foreach($books as $book)
        <a href="/books/{{$book->id}}">
        <div class="book p-2.5 border-t-2">
            <h2 class="text-2xl font-semibold italic">{{$book->title}}</h2>
            <div class="book-details">
                <p>ISBN: {{$book->isbn}}</p>
                <p>Author: {{$book->author}}</p>
                <p>Description: {{Illuminate\Support\Str::limit($book->description, 100) }}</p>
            </div>
        </div>
        </a>
        @endforeach
    </div>
</div>
@include('partials.footer')
@include('partials.header')
<x-nav />
<div class="grid grid-cols-3">
    <div class="books col-span-2 mx-10">
        <div class="book">
            <h2 class="text-2xl font-semibold italic">Book Name</h2>
            <div class="book-details">
                <p>Test 1, Test 2, Test 3</p>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Debitis odit repellendus earum minima, sunt, corporis beatae vel in expedita illum aspernatur magnam temporibus nisi libero quia enim nesciunt qui unde.</p>
                <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Green</button>

            </div>
        </div>
    </div>
    <div class="book-categories">
        
    <h2 class="text-1xl font-semibold italic">Book Categories</h2>
    
    </div>
</div>
@include('partials.footer')
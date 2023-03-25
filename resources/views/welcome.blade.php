@include('partials.header')
    <x-nav />

    <!-- Hero section -->
    <section class="bg-whitebg-no-repeat h-[75vh] bg-cover" style="background-image: url({{url('/images/UPang.jpg')}})">
        <div class="h-full w-full flex justify-center items-center bg-black bg-opacity-70">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-[#19b032] md:text-5xl lg:text-6xl">PHINMA-UPANG COLLEGE OF URDANETA</h1>
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">LIBRARY MANAGEMENT SYSTEM</h1>
                <p class="mb-8 text-4xl font-normal text-white lg:text-xl sm:px-16 xl:px-48">Explore and Borrow in our University Library</p>
            </div>
        </div>
    </section>

@include('partials.footer')

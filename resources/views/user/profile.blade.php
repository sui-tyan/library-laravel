@include('partials.header')
<x-nav />

<div class="grid w-3/4 mx-auto grid-cols-1 lg:grid-cols-2 mb-5">
  <div class="profile mt-2">
    <div class="image-container border rounded-md w-full lg:w-9/12 p-10">
      <img src="{{url('/images/user.png')}}" style="width: 100px" class="rounded-full mx-auto my-5" alt="profile image">

      <ul class="text-sm font-medium text-gray-900 bg-white rounded-lg text-center lg:text-left mb-2.5">
        <input type="file" name="profile-image" id="">
        <div class="relative z-0 w-full mb-6 group mt-5">
          <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
          <label for="password" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Change Password</label>
        </div>
      </ul>
    </div>
    <div class="mt-5">
      <a href="/logout" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Logout</a>

    </div>
  </div>
  <div class="profile-details">
    <h2 class="text-2xl font-semibold text-center lg:text-left m-5 lg:ml-0">Account Details</h2>

    <form method="POST" action="/updateProfile">
      @csrf
      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="studentID" id="studentID" value="{{$user->studentID}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="studentID" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Student ID</label>
      </div>
      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="firstName" id="firstName" value="{{$user->firstName}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="firstName" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First Name</label>
      </div>

      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="middleName" id="middleName" value="{{$user->middleName}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="middleName" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Middle Name</label>
      </div>
      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="lastName" id="lastName" value="{{$user->lastName}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="lastName" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last Name</label>
      </div>
      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="address" id="address" value="{{$user->address}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="address" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Address</label>
      </div>

      <div class="relative z-0 w-full mb-6 group">
        <h4 class="text-sm text-gray-500 py-2.5">Gender</h4>
        <div class="flex">
          <div class="flex items-center mr-4">
            @if($user->gender === "Male")
            <input id="inline-2-radio" type="radio" value="Male" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" checked>
            <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900">Male</label>
            @else
            <input id="inline-2-radio" type="radio" value="Male" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
            <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900">Male</label>
            @endif
          </div>
          <div class="flex items-center mr-4">
            @if($user->gender === "Female")
            <input id="inline-radio" type="radio" value="Female" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" checked>
            <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900">Female</label>
            @else
            <input id="inline-radio" type="radio" value="Female" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
            <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900">Female</label>
            @endif
          </div>
        </div>
      </div>

      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="contactNumber" id="contactNumber" value="{{$user->contactNumber}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="contactNumber" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Contact No.</label>
      </div>
      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="courseAndYear" id="courseAndYear" value="{{$user->courseAndYear}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="courseAndYear" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Course and Year</label>
      </div>
      <div class="relative z-0 w-full mb-6 group">
        <input type="text" name="deparment" id="deparment" value="{{$user->department}}" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="deparment" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Department</label>
      </div>
      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
    </form>

  </div>
</div>


@include('partials.footer')
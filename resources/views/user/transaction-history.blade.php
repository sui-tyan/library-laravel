@include('partials.header')
<nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded ">
  <div class="container flex flex-wrap items-center justify-between mx-auto">
    <a href="/" class="flex items-center">
      <img src="{{url('/images/logo.png')}}" class="h-20 mr-3" alt="UPANG" />
      <span class="self-center text-xl font-semibold whitespace-nowrap hidden md:block">PUCU Library System</span>
    </a>
    <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-default" aria-expanded="false">
      <span class="sr-only">Open main menu</span>
      <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
      </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-default">
      <ul class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white ">
        <li>
          <a href="/" class="relative mr-2 text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group" aria-current="page">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="text-sm font-semibold ml-1">
              Home
            </span>
          </a>
        </li>
        <li>
          <a href="/books" class="relative mr-2 text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>

            <span class="text-sm font-semibold ml-1">
              Books
            </span>
          </a>
        </li>
        <li>
          <a href="/find-books" class="relative mr-2 text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="text-sm font-semibold ml-1">
              Find Books
            </span>
          </a>
        </li>
        @if (Auth::check())
        <li>
          <a href="/profile" class="relative mr-2 text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm font-semibold ml-1">
              Profile
            </span>
          </a>
        </li>


        <li>
          <button id="dropdownLeftEndButton" data-dropdown-toggle="dropdownLeftEnd" data-dropdown-placement="left-end" class="relative mr-2 text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            <span class="text-sm font-semibold ml-1">
              Notification
            </span>
            @if(count($notifications) > 0)
            <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2 dark:border-gray-900">
              {{count($notifications)}}
            </div>
            @endif
          </button>

          <div id="dropdownLeftEnd" class="z-20 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-1/4 dark:bg-gray-700">

            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton">
              @if(count($notifications) == 0)
              <li>
                <a class="block px-4 py-2 ">
                  No notifications.
                </a>
              </li>
              @endif
              @foreach($notifications as $notification)
              <li>
                <a href="/admin/seen/{{$notification->id}}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  Your book is ready for pickup failure to pick up will decline your transaction
                </a>
              </li>
              @endforeach
            </ul>
          </div>
        </li>

        @else
        <li>
          <a href="/login" class="relative mr-2 text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33" />
            </svg>

            <span class="text-sm font-semibold ml-1">
              Login
            </span>
          </a>
        </li>
        @endif

      </ul>
    </div>
  </div>
</nav>
<div class="grid w-3/4 mx-auto grid-cols-1 mb-5">
  <div class="profile-details">
    <h2 class="text-2xl font-semibold text-center lg:text-left m-5 lg:ml-0">Transaction History</h2>
    <div class="flex flex-col mt-8">
      <div class="overflow-x-auto rounded-lg">
        <div class="align-middle inline-block min-w-full">
          <div class="shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    ISBN/ISSN
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Title
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Borrower
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Purpose
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date Borrowed
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Due Date
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date Returned
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Remarks
                  </th>
                  <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                </tr>
              </thead>
              <tbody class="bg-gray-50 border">
                @foreach($transactions as $transaction)
                <tr class="border whitespace-nowrap text-sm font-semibold text-gray-900">

                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    @if($transaction->isbn == 'none' && $transaction->issn == 'none')
                    N/A
                    @elseif($transaction->isbn != "none")
                    {{$transaction->isbn}}
                    @else if($transaction->issn != 'none')
                    {{$transaction->issn}}
                    @endif
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->title}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
                    {{$transaction->categories}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->borrower}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->purpose}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->dateBorrowed}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->dueDate}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->dateReturned}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->remarks}}
                  </td>
                  <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    {{$transaction->status}}
                  </td>
                  @endforeach
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@include('partials.footer')
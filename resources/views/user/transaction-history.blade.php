@include('partials.header')
<x-nav />

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
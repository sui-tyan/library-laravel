<table class="min-w-full divide-y divide-gray-200">
  <thead class="bg-gray-50">
    <tr>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        ID
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        ISBN
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        ISSN
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        TITLE
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        AUTHOR
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        PUBLISHER
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        DESCRIPTION
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        DEWEY CHARACTER
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        CATEGORY
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        PRICE
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        PUBLISHED DATE
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        TYPE
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        STATUS
      </th>
      <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
        REMARKS
      </th>
    </tr>
  </thead>
  <tbody class="bg-gray-50 border">
    @foreach($user as $user)
    <tr class="border">
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->id}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500">
        {{$user->isbn}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->issn}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->title}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->author}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->publisher}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->description}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->deweyDecimal}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->categories}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->price}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->publishedDate}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->type}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->status}}
      </td>
      <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900">
        {{$user->remarks}}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
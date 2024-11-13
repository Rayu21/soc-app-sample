<x-profile :sharedData="$sharedData" doctitle="who {{$sharedData['username'] }} Follows">
    @include('profile-following-raw')
</x-profile>

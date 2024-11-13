<div class="list-group">
    @foreach($following as $follow)
    @if(auth()->user()->username == $follow->userBeingFollowed->username)
        <!-- If the follower is the authenticated user, direct them to their own profile -->
        <a href="{{ route('profile.show', ['user' => auth()->user()->username]) }}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{ auth()->user()->avatar }}" />
          {{ auth()->user()->username }} (You)
        </a>
    @else
        <!-- If the follower is someone else, direct to their profile -->
        <a href="{{ route('profile.show', ['user' => $follow->userBeingFollowed->username]) }}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follow->userBeingFollowed->avatar}}" />
          {{$follow->userBeingFollowed->username}}
        </a>
    @endif
    @endforeach
</div>

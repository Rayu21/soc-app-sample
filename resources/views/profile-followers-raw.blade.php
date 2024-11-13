<div class="list-group">
    @foreach($followers as $follow)

    <!-- If the follower is someone else, direct to their profile -->
    <a href="{{ route('profile.show', ['user' => $follow->userDoingTheFollowing->username]) }}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{$follow->userDoingTheFollowing->avatar}}" />
        {{$follow->userDoingTheFollowing->username}}
      </a>
    @endforeach
  </div>

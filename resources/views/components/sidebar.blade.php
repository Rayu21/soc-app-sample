<div class="sidebar">

    <ul class="sidebar-menu">
        <li class="menu-item active">
            <a href="{{ route('login') }}" class="menu-link">
                <i class="fas fa-home"></i>

            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="menu-link">
                <i class="fas fa-user"></i>

            </a>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="fas fa-users"></i>

            </a>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="fas fa-bell"></i>

            </a>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="fas fa-envelope"></i>

            </a>
        </li>


        </li>
    </ul>
</div>

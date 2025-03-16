<nav class="navbar navbar-expand-lg navbar-light bg-light" style="align-items: center;">
    <a class="navbar-brand" href="{{ route('getItems') }}" style="line-height: 50px;">HomeEssence</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('getItems') }}" style="line-height: 50px;">Home<span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item dropdown" style="line-height: 50px;">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="line-height: 50px;">
                    {{-- Show profile picture if available; otherwise, show default icon --}}
                    @if(Auth::check() && Auth::user()->customer && Auth::user()->customer->profile_picture)
                        <img src="{{ Storage::url('profile_pictures/' . Auth::user()->customer->profile_picture) }}" 
                             alt="Profile Picture" class="rounded-circle" 
                             style="width:50px; height:50px; margin-right:10px; object-fit: cover; vertical-align: middle;">
                    @else
                        <i class="fas fa-user-circle" style="font-size:50px; vertical-align: middle; margin-right:10px;"></i>
                    @endif
                    {{ Auth::check() ? Auth::user()->name : '' }}
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <a class="dropdown-item py-1" href="{{ route('customerprofile.edit') }}">Profile</a>
                        <a class="dropdown-item py-1" href="{{ route('reviews.reviewable_items') }}">Reviews</a>
                        <a class="dropdown-item py-1" href="{{ route('admin.orders') }}">Orders</a>
                        <a class="dropdown-item py-1" href="{{ route('admin.users') }}">Users</a>
                        <a class="dropdown-item py-1" href="{{ route('admin.items') }}">Items</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                    @elseif (Auth::check())
                        <a class="dropdown-item" href="{{ route('customerprofile.edit') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('reviews.reviewable_items') }}">Reviews</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('user.logout') }}">Logout</a>
                    @else
                        <a class="dropdown-item" href="{{ route('register') }}">Signup</a>
                        <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                    @endif
                </div>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item" style="line-height: 50px;">
                <a class="nav-link" href="{{ route('getCart') }}">
                    <i class="fa-solid fa-cart-shopping"></i> Shopping Cart
                    <span class="badge rounded-pill bg-danger">
                        {{ Session::has('cart') ? Session::get('cart')->totalQty : '' }}
                    </span>
                </a>
            </li>
        </ul>

        <form action="{{ route('search') }}" method="GET" class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="term">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</nav>

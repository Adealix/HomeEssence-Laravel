
@section('content')
<div class="container">
    <h1>Email Verification</h1>
    <p>Please verify your email before proceeding.</p>
    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>
</div>
@endsection

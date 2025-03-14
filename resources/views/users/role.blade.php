<form action="{{ route('users.update', $row->id) }}" method="POST">
    @csrf
    @method('PUT') <!-- Spoofing the HTTP method to PUT -->
    <select name="role" class="form-select" aria-label="Default select example">
        <option value="user" {{ $row->role == 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ $row->role == 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<form action="{{ route('users.update', $row->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-2">
        <select name="role" id="role-{{ $row->id }}" class="form-select" aria-label="Role select">
            <option value="user" {{ $row->role == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $row->role == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm">Update Role</button>
</form>

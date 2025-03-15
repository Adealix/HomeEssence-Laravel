<form action="{{ route('users.update', $row->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-2">
        <select name="status" id="status-{{ $row->id }}" class="form-select" aria-label="Status select">
            <option value="active" {{ $row->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $row->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-sm mt-1">Update Status</button>
</form>

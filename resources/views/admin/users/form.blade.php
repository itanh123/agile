<form method="POST" action="{{ $action }}" class="card card-body">
    @csrf @if($method !== 'POST') @method($method) @endif
    <div class="row">
        <div class="col-md-6 mb-2"><label>Full name</label><input name="full_name" class="form-control" value="{{ old('full_name', $user->full_name ?? '') }}"></div>
        <div class="col-md-6 mb-2"><label>Email</label><input name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}"></div>
        <div class="col-md-4 mb-2"><label>Phone</label><input name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}"></div>
        <div class="col-md-4 mb-2"><label>Address</label><input name="address" class="form-control" value="{{ old('address', $user->address ?? '') }}"></div>
        <div class="col-md-4 mb-2"><label>Role</label><select name="role_id" class="form-select">@foreach($roles as $role)<option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>{{ $role->name }}</option>@endforeach</select></div>
        <div class="col-md-6 mb-2"><label>Password</label><input name="password" type="password" class="form-control"></div>
    </div>
    <button class="btn btn-primary">Save</button>
</form>

<form method="POST" action="{{ $action }}" class="card card-body">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="row">
        <div class="col-md-6 mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $pet->name ?? '') }}"></div>
        <div class="col-md-3 mb-3"><label>Category</label><select name="category_id" class="form-select">@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $pet->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="col-md-3 mb-3"><label>Breed</label><select name="breed_id" class="form-select">@foreach($breeds as $breed)<option value="{{ $breed->id }}" @selected(old('breed_id', $pet->breed_id ?? '') == $breed->id)>{{ $breed->name }}</option>@endforeach</select></div>
        <div class="col-md-3 mb-3"><label>Gender</label><select name="gender" class="form-select"><option value="male">male</option><option value="female">female</option><option value="unknown">unknown</option></select></div>
        <div class="col-md-3 mb-3"><label>Color</label><input name="color" class="form-control" value="{{ old('color', $pet->color ?? '') }}"></div>
        <div class="col-md-3 mb-3"><label>Weight</label><input name="weight" class="form-control" value="{{ old('weight', $pet->weight ?? '') }}"></div>
        <div class="col-md-3 mb-3"><label>Health status</label><input name="health_status" class="form-control" value="{{ old('health_status', $pet->health_status ?? '') }}"></div>
        <div class="col-12 mb-3"><label>Note</label><textarea name="notes" class="form-control">{{ old('notes', $pet->notes ?? '') }}</textarea></div>
    </div>
    <button class="btn btn-primary">Save</button>
</form>

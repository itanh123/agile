<form method="POST" action="{{ $action }}" class="card card-body">
    @csrf @if($method !== 'POST') @method($method) @endif
    <div class="row">
        <div class="col-md-6 mb-2"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $service->name ?? '') }}"></div>
        <div class="col-md-3 mb-2"><label>Type</label><select name="service_type" class="form-select">@foreach(['grooming','vaccination','spa','checkup','surgery'] as $type)<option value="{{ $type }}" @selected(old('service_type', $service->service_type ?? '')==$type)>{{ $type }}</option>@endforeach</select></div>
        <div class="col-md-3 mb-2"><label>Duration</label><input name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $service->duration_minutes ?? 30) }}"></div>
        <div class="col-md-4 mb-2"><label>Price</label><input name="price" class="form-control" value="{{ old('price', $service->price ?? '') }}"></div>
        <div class="col-md-8 mb-2"><label>Description</label><input name="description" class="form-control" value="{{ old('description', $service->description ?? '') }}"></div>
        <div class="col-md-3 mb-2"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))> Active</label></div>
    </div>
    <button class="btn btn-primary">Save</button>
</form>

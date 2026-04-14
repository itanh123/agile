<form method="POST" action="{{ $action }}" class="card card-body">
    @csrf @if($method !== 'POST') @method($method) @endif
    <div class="row">
        <div class="col-md-3 mb-2"><label>Code</label><input name="code" class="form-control" value="{{ old('code', $promotion->code ?? '') }}"></div>
        <div class="col-md-5 mb-2"><label>Title</label><input name="title" class="form-control" value="{{ old('title', $promotion->title ?? '') }}"></div>
        <div class="col-md-2 mb-2"><label>Type</label><select name="discount_type" class="form-select"><option value="percent">percent</option><option value="fixed">fixed</option></select></div>
        <div class="col-md-2 mb-2"><label>Value</label><input name="discount_value" class="form-control" value="{{ old('discount_value', $promotion->discount_value ?? '') }}"></div>
        <div class="col-md-4 mb-2"><label>Start</label><input name="start_at" type="datetime-local" class="form-control" value="{{ old('start_at') }}"></div>
        <div class="col-md-4 mb-2"><label>End</label><input name="end_at" type="datetime-local" class="form-control" value="{{ old('end_at') }}"></div>
        <div class="col-md-4 mb-2"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion->is_active ?? true))> Active</label></div>
        <div class="col-12 mb-2"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $promotion->description ?? '') }}</textarea></div>
    </div>
    <button class="btn btn-primary">Save</button>
</form>

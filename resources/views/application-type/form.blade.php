{{ csrf_field() }}
<div class="form-group">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="status_enabled" value="1" @if($applicationType->status == 1) checked @endif>
        <label class="form-check-label" for="status_enabled">
            Enabled
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" id="status_disabled" value="0" @if($applicationType->status == 0) checked @endif >
        <label class="form-check-label" for="status_disabled">
            Disabled
        </label>
    </div>
</div>
<div class="form-group">
    <label for="image">Image</label>
    <input type="file" name="image" class="form-control-file" />
    @if ($applicationType->image)
        <img src="{{ asset('storage/' . $applicationType->image) }}" />
    @endif
</div>

@if ($applicationType->image)
    <div class="form-group">
        <input type="checkbox" name="deleteimage" id="deleteimage" />
        <label for="deleteimage">
            <i class="fa-2x fa fa-trash"></i> Delete image ({{ $applicationType->image }})
        </label>
    </div>
@endif

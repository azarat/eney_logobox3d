{{ csrf_field() }}
<div class="form-group">
    <label for="domain">Domain</label>
    <input name="domain" type="text" class="form-control @if ($errors->has('domain')) is-invalid @endif" id="domain" value="{{ old('domain', optional($domain)->domain) }}">
    @foreach ($errors->get('domain') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="key">Key</label>
    <input name="key" type="text" class="form-control @if ($errors->has('key')) is-invalid @endif" id="key" value="{{ old('key', optional($domain)->key) }}">
    @foreach ($errors->get('key') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="coefficient">Coefficient</label>
    <input name="coefficient" type="text" class="form-control @if ($errors->has('coefficient')) is-invalid @endif" id="coefficient" value="{{ old('coefficient', optional($domain)->coefficient) }}">
    @foreach ($errors->get('coefficient') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="main_button_color">Main button color</label>
    <input name="main_button_color" type="text" class="form-control @if ($errors->has('main_button_color')) is-invalid @endif" id="main_button_color" value="{{ old('main_button_color', optional($domain)->main_button_color) }}">
    @foreach ($errors->get('main_button_color') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="main_button_color_hover">Main button color hover</label>
    <input name="main_button_color_hover" type="text" class="form-control @if ($errors->has('main_button_color_hover')) is-invalid @endif" id="main_button_color_hover" value="{{ old('main_button_color_hover', optional($domain)->main_button_color_hover) }}">
    @foreach ($errors->get('main_button_color_hover') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="main_button_color_active">Main button color active</label>
    <input name="main_button_color_active" type="text" class="form-control @if ($errors->has('main_button_color_active')) is-invalid @endif" id="main_button_color_active" value="{{ old('main_button_color_active', optional($domain)->main_button_color_active) }}">
    @foreach ($errors->get('main_button_color_active') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="panelTextColor">Panel text color</label>
    <input name="panel_text_color" type="text" class="form-control @if ($errors->has('panel_text_color')) is-invalid @endif" id="panelTextColor" value="{{ old('panel_text_color', optional($domain)->panel_text_color) }}">
    @foreach ($errors->get('panel_text_color') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="addButtonColor">Add button color</label>
    <input name="add_button_color" type="text" class="form-control @if ($errors->has('add_button_color')) is-invalid @endif" id="addButtonColor" value="{{ old('add_button_color', optional($domain)->add_button_color) }}">
    @foreach ($errors->get('add_button_color') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="bgColor">Background color</label>
    <input name="bg_color" type="text" class="form-control @if ($errors->has('bg_color')) is-invalid @endif" id="bgColor" value="{{ old('bg_color', optional($domain)->bg_color) }}">
    @foreach ($errors->get('bg_color') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>
<div class="form-group">
    <label for="labelTextColor">Label text color</label>
    <input name="label_text_color" type="text" class="form-control @if ($errors->has('label_text_color')) is-invalid @endif" id="labelTextColor" value="{{ old('label_text_color', optional($domain)->label_text_color) }}">
    @foreach ($errors->get('label_text_color') as $message)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @endforeach
</div>

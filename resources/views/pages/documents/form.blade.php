<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ 'Name' }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($document->name) ? $document->name : ''}}" required>
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('file') ? 'has-error' : ''}}">
    <label for="file" class="control-label">{{ 'File' }}</label>
    <input class="form-control" name="file" type="text" id="file" value="{{ isset($document->file) ? $document->file : ''}}" required>
    {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="number" id="status" value="{{ isset($document->status) ? $document->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    <label for="type" class="control-label">{{ 'Type' }}</label>
    <input class="form-control" name="type" type="number" id="type" value="{{ isset($document->type) ? $document->type : ''}}" >
    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('publish_date') ? 'has-error' : ''}}">
    <label for="publish_date" class="control-label">{{ 'Publish Date' }}</label>
    <input class="form-control" name="publish_date" type="text" id="publish_date" value="{{ isset($document->publish_date) ? $document->publish_date : ''}}" >
    {!! $errors->first('publish_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('expiration_date') ? 'has-error' : ''}}">
    <label for="expiration_date" class="control-label">{{ 'Expiration Date' }}</label>
    <input class="form-control" name="expiration_date" type="text" id="expiration_date" value="{{ isset($document->expiration_date) ? $document->expiration_date : ''}}" >
    {!! $errors->first('expiration_date', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('assigned_user_id') ? 'has-error' : ''}}">
    <label for="assigned_user_id" class="control-label">{{ 'Assigned User Id' }}</label>
    <input class="form-control" name="assigned_user_id" type="number" id="assigned_user_id" value="{{ isset($document->assigned_user_id) ? $document->assigned_user_id : ''}}" >
    {!! $errors->first('assigned_user_id', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>

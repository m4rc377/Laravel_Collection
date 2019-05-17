<div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
    <label for="first_name" class="control-label">{{ 'First Name' }}</label>
    <input class="form-control" name="first_name" type="text" id="first_name" value="{{ isset($contact->first_name) ? $contact->first_name : ''}}" >
    {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('middle_name') ? 'has-error' : ''}}">
    <label for="middle_name" class="control-label">{{ 'Middle Name' }}</label>
    <input class="form-control" name="middle_name" type="text" id="middle_name" value="{{ isset($contact->middle_name) ? $contact->middle_name : ''}}" >
    {!! $errors->first('middle_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
    <label for="last_name" class="control-label">{{ 'Last Name' }}</label>
    <input class="form-control" name="last_name" type="text" id="last_name" value="{{ isset($contact->last_name) ? $contact->last_name : ''}}" >
    {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="number" id="status" value="{{ isset($contact->status) ? $contact->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('referral_source') ? 'has-error' : ''}}">
    <label for="referral_source" class="control-label">{{ 'Referral Source' }}</label>
    <input class="form-control" name="referral_source" type="text" id="referral_source" value="{{ isset($contact->referral_source) ? $contact->referral_source : ''}}" >
    {!! $errors->first('referral_source', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('position_title') ? 'has-error' : ''}}">
    <label for="position_title" class="control-label">{{ 'Position Title' }}</label>
    <input class="form-control" name="position_title" type="text" id="position_title" value="{{ isset($contact->position_title) ? $contact->position_title : ''}}" >
    {!! $errors->first('position_title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('industry') ? 'has-error' : ''}}">
    <label for="industry" class="control-label">{{ 'Industry' }}</label>
    <input class="form-control" name="industry" type="text" id="industry" value="{{ isset($contact->industry) ? $contact->industry : ''}}" >
    {!! $errors->first('industry', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('project_type') ? 'has-error' : ''}}">
    <label for="project_type" class="control-label">{{ 'Project Type' }}</label>
    <input class="form-control" name="project_type" type="text" id="project_type" value="{{ isset($contact->project_type) ? $contact->project_type : ''}}" >
    {!! $errors->first('project_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('company_id') ? 'has-error' : ''}}">
    <label for="company_id" class="control-label">{{ 'Company Id' }}</label>
    <input class="form-control" name="company_id" type="text" id="company_id" value="{{ isset($contact->company_id) ? $contact->company_id : ''}}" >
    {!! $errors->first('company_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('project_description') ? 'has-error' : ''}}">
    <label for="project_description" class="control-label">{{ 'Project Description' }}</label>
    <textarea class="form-control" rows="5" name="project_description" type="textarea" id="project_description" >{{ isset($contact->project_description) ? $contact->project_description : ''}}</textarea>
    {!! $errors->first('project_description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('budget') ? 'has-error' : ''}}">
    <label for="budget" class="control-label">{{ 'Budget' }}</label>
    <input class="form-control" name="budget" type="text" id="budget" value="{{ isset($contact->budget) ? $contact->budget : ''}}" >
    {!! $errors->first('budget', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('website') ? 'has-error' : ''}}">
    <label for="website" class="control-label">{{ 'Website' }}</label>
    <input class="form-control" name="website" type="text" id="website" value="{{ isset($contact->website) ? $contact->website : ''}}" >
    {!! $errors->first('website', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('linkedin') ? 'has-error' : ''}}">
    <label for="linkedin" class="control-label">{{ 'Linkedin' }}</label>
    <input class="form-control" name="linkedin" type="text" id="linkedin" value="{{ isset($contact->linkedin) ? $contact->linkedin : ''}}" >
    {!! $errors->first('linkedin', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('address_street') ? 'has-error' : ''}}">
    <label for="address_street" class="control-label">{{ 'Address Street' }}</label>
    <input class="form-control" name="address_street" type="text" id="address_street" value="{{ isset($contact->address_street) ? $contact->address_street : ''}}" >
    {!! $errors->first('address_street', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('address_city') ? 'has-error' : ''}}">
    <label for="address_city" class="control-label">{{ 'Address City' }}</label>
    <input class="form-control" name="address_city" type="text" id="address_city" value="{{ isset($contact->address_city) ? $contact->address_city : ''}}" >
    {!! $errors->first('address_city', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('address_state') ? 'has-error' : ''}}">
    <label for="address_state" class="control-label">{{ 'Address State' }}</label>
    <input class="form-control" name="address_state" type="text" id="address_state" value="{{ isset($contact->address_state) ? $contact->address_state : ''}}" >
    {!! $errors->first('address_state', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('address_country') ? 'has-error' : ''}}">
    <label for="address_country" class="control-label">{{ 'Address Country' }}</label>
    <input class="form-control" name="address_country" type="text" id="address_country" value="{{ isset($contact->address_country) ? $contact->address_country : ''}}" >
    {!! $errors->first('address_country', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('address_zipcode') ? 'has-error' : ''}}">
    <label for="address_zipcode" class="control-label">{{ 'Address Zipcode' }}</label>
    <input class="form-control" name="address_zipcode" type="text" id="address_zipcode" value="{{ isset($contact->address_zipcode) ? $contact->address_zipcode : ''}}" >
    {!! $errors->first('address_zipcode', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('assigned_user_id') ? 'has-error' : ''}}">
    <label for="assigned_user_id" class="control-label">{{ 'Assigned User Id' }}</label>
    <input class="form-control" name="assigned_user_id" type="number" id="assigned_user_id" value="{{ isset($contact->assigned_user_id) ? $contact->assigned_user_id : ''}}" >
    {!! $errors->first('assigned_user_id', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>

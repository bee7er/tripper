@extends('admin.layouts.modal')
{{-- Content --}}
@section('content')
        <!-- Tabs -->
<ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"> {{
			trans("admin/modal.general") }}</a></li>
</ul>
<!-- ./ tabs -->
@if (isset($resource))
{!! Form::model($resource, array('url' => url('admin/resource') . '/' . $resource->id, 'method' => 'put','id'=>'fupload','class' => 'bf', 'files'=> true)) !!}
@else
{!! Form::open(array('url' => url('admin/resource'), 'method' => 'post', 'class' => 'bf','id'=>'fupload', 'files'=> true)) !!}
@endif
        <!-- Tabs Content -->
<div class="tab-content">
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
        <div class="form-group  {{ $errors->has('template_id') ? 'has-error' : '' }}">
            {!! Form::label('template_id', trans("admin/admin.template"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('template_id', $templates, @isset($resource)? $resource->template_id : 'default', array
                ('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('template_id', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans("admin/modal.title"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('description') ? 'has-error' : '' }}">
            {!! Form::label('description', trans("admin/resource.description"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('description', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('thumb') ? 'error' : '' }}">
            <div class="col-lg-12">
                <label class="control-label" for="thumb">
                    {{ trans("admin/resource.thumb") }}</label>
                <input name="thumb" type="file" class="uploader" id="thumb" value="Upload"/>
                @if (isset($resource) && $resource->thumb)
                    <img src="/appfiles/resource/{{$resource->thumb}}" width="80">
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('image') ? 'error' : '' }}">
            <div class="col-lg-12">
                <label class="control-label" for="image">
                    {{ trans("admin/resource.image") }}</label>
                <input name="image" type="file" class="uploader" id="image" value="Upload"/>
                @if (isset($resource) && $resource->image)
                    <img src="/appfiles/resource/{{$resource->image}}" width="80">
                @endif
            </div>
        </div>
        <div class="form-group  {{ $errors->has('url') ? 'has-error' : '' }}">
            {!! Form::label('url', trans("admin/resource.url"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('url', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('url', ':message') }}</span>
            </div>
        </div>
        <!-- ./ general tab -->
    </div>
    <!-- ./ tabs content -->

    <!-- Form Actions -->

    <div class="form-group">
        <div class="col-md-12">
            <button type="reset" class="btn btn-sm btn-warning close_popup">
                <span class="glyphicon glyphicon-ban-circle"></span> {{
						trans("admin/modal.cancel") }}
            </button>
            <button type="reset" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-remove-circle"></span> {{
						trans("admin/modal.reset") }}
            </button>
            <button type="submit" class="btn btn-sm btn-success">
                <span class="glyphicon glyphicon-ok-circle"></span>
                @if	(isset($resource))
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
        </div>
    </div>
    <!-- ./ form actions -->
{!! Form::close() !!}
</div>
@endsection

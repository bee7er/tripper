@extends('admin.layouts.modal')
{{-- Content --}}
@section('content')

@if (isset($question) && $question->id)
{!! Form::model($question, array('url' => url('admin/question') . '/' . $question->id, 'method' => 'put','id'=>'fupload')) !!}
@else
{!! Form::open(array('url' => url('admin/question'), 'method' => 'post', 'id'=>'fupload')) !!}
@endif
        <!-- Tabs Content -->
<div class="tab-content">
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
        <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
            {!! Form::label('type', trans("admin/question.type"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! App_Model_Question::getTypes($question->type) !!}
                <span class="help-block">{{ $errors->first('type', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('label') ? 'has-error' : '' }}">
            {!! Form::label('label', trans("admin/question.label"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('label', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('label', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('question') ? 'has-error' : '' }}">
            {!! Form::label('question', trans("admin/question.question"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('question', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('question', ':message') }}</span>
            </div>
        </div>
        <div class="form-group  {{ $errors->has('required') ? 'has-error' : '' }}">
            {!! Form::label('equired', trans("admin/question.required"), array('class' => 'control-label')) !!}
            <div class="controls">
                <select class="form-control" name="required" id="required">
                    <option value="1" {{ $question->required ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$question->required ? 'selected' : '' }}>No</option>
                </select>
                <span class="help-block">{{ $errors->first('required', ':message') }}</span>
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
                @if	(isset($question) && $question->id)
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

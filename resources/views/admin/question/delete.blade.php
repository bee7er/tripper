@extends('admin.layouts.modal')
@section('content')
	{!! Form::model($question, array('url' => url('admin/question') . '/' . $question->id, 'method' => 'delete')) !!}
	<div class="form-group">
		<div class="controls">
			<div style="margin-bottom:10px;font-weight:bold;font-size:14px;">{!! $question->label !!}</div>

			{{ trans("admin/modal.delete_message") }}<br>
			<element class="btn btn-warning btn-sm close_popup">
				<span class="glyphicon glyphicon-ban-circle"></span> {{
			trans("admin/modal.cancel") }}</element>
			<button type="submit" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-trash"></span> {{
				trans("admin/modal.delete") }}
			</button>
		</div>
	</div>
	{!! Form::close() !!}
@endsection

@extends('admin.layouts.modal')
{{-- Content --}}
@section('content')

@if (isset($clist))
{!! Form::model($clist, array('url' => url('admin/clist') . '/' . $clist->id, 'method' => 'put','id'=>'fupload')) !!}
@else
{!! Form::open(array('url' => url('admin/clist'), 'method' => 'post', 'id'=>'fupload')) !!}
@endif
        <!-- Tabs Content -->
<div class="tab-content">
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
        <div class="form-group  {{ $errors->has('label') ? 'has-error' : '' }}">
            {!! Form::label('label', trans("admin/clist.label"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('label', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('label', ':message') }}</span>
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
                @if	(isset($clist))
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

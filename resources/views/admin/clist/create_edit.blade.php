@extends('admin.layouts.modal')
{{-- Content --}}
@section('content')

@if (isset($clist) && $clist->id)
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

    <input type="hidden" name="clistId" id="clistId" value="{{ $clist->id }}">
    <table id="table" class="table table-sclisted table-hover">
        <thead>
        <tr>
            <th>{!! trans("admin/constant.label") !!}</th>
            <th>{!! trans("admin/constant.value") !!}</th>
            <th>{!! trans("admin/admin.created_at") !!}</th>
            <th>{!! trans("admin/admin.updated_at") !!}</th>
            <th>{!! trans("admin/admin.action") !!}</th>
        </tr>
        </thead>
        <tbody>
        @if(count($constants)>0)
            @foreach ($constants as $constant)
                <tr>
                    <td>{!! $constant->label !!}</td>
                    <td>{!! $constant->value !!}</td>
                    <td>{!! $constant->created_at !!}</td>
                    <td>{!! $constant->updated_at !!}</td>
                    <th>
                        <button type="submit" id="edit_{!! $constant->id !!}" class="btn btn-sm btn-success row-action">
                            <span class="glyphicon glyphicon-pencil"></span>
                            {!! trans("admin/admin.edit") !!}
                        </button>
                        <button type="submit" id="delete_{!! $constant->id !!}" class="btn btn-sm btn-danger row-action">
                            <span class="glyphicon glyphicon-trash"></span>
                            {!! trans("admin/admin.delete") !!}
                        </button>
                    </th>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

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
                @if	(isset($clist) && $clist->id)
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
            <button type="submit" id="addConstant" class="btn btn-sm btn-primary">
                <span class="glyphicon glyphicon-plus-sign"></span>
                    {{ trans("admin/constant.add") }}
            </button>
        </div>
    </div>
    <!-- ./ form actions -->
{!! Form::close() !!}
</div>

@include('partials.forms')
@include('partials.clist-js')

@endsection
{{-- Scripts --}}
@section('scripts')

@endsection

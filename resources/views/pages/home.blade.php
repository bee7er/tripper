@extends('layouts.app')
@section('title') home @parent @endsection

@section('content')

    <div style="margin: 10px;">

        @if(count($tree)>0)
            <div class="row-container">
                <div class="row" style="text-align: left;">
                    @foreach($tree as $twig)
                        @foreach($twig->entries as $entry)
                            <div>{!! $entry !!}</div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            <div class="go-top" onclick="scrollToAnchor('top');">
                @if($loggedIn)
                    <div style="color: white;text-align: center;">{{ $user->username }} is logged in</div>
                @endif
            </div>
        @endif

    </div>

@endsection

@section('page-scripts')
    <script type="text/javascript">

        $(document).ready( function()
        {

        });
    </script>
@endsection

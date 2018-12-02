
<div id="top">&nbsp;</div>

<div class="row logo-menu-container">
    @if (Request::is('/') || Request::is('home'))
        <div class="hidden-xs hidden-sm col-md-12 col-lg-12 header-block" style="margin-bottom: 0px !important;">
            <div class="row logo-menu-row">
                <div style="margin-bottom: -10px !important;"><span onclick="login();" class="go-top">Trips and things</span></div>
            </div>
        </div>
    @endif
</div>

@section('global-scripts')
    <script type="text/javascript">
        function login()
        {
            document.location = ("{{config('app.base_url')}}" + "auth/login");
        }

        function gotoPage(aid)
        {
            @if (Request::is('/') || Request::is('home'))
                scrollToAnchor(aid);
            @else
                if (aid == "home") {
                    document.location = ("{{config('app.base_url')}}");
                } else {
                    document.location = ("{{config('app.base_url')}}" + "home#" + aid);
                }
            @endif
        }

        function scrollToAnchor(aid)
        {
            var aTag = $("div[id='"+ aid +"']");
            $('html,body').animate({scrollTop: aTag.offset().top},'slow');
        }
    </script>
@endsection

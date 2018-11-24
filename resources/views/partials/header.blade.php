
<div id="top">&nbsp;</div>

<div class="row logo-menu-container">

    <!-- The non-breaking spaces with 'work' force the logo image to be centered above the thumbs -->
    <div class="hidden-xs hidden-sm col-md-12 col-lg-12 header-block">
        <table style="width: 56%;margin-left:22%;margin-right:22%;" border="0">
            <tbody>
            <tr style="vertical-align: middle !important;">
                <td>&nbsp;</td>
                <td rowspan="3" style="width:220px;"><span onclick="login();" class="go-top"><img src="{{config('app.base_url')}}img/sqsqboxlesslogo.png" style="margin-right:-4px;border: 0px solid green;"/></span></td>
                <td>&nbsp;</td>
            </tr>
            <tr style="vertical-align: middle !important;">
                <td style="text-align:right;margin-right: 0px;"><img src="{{config('app.base_url')}}img/sqsqbannertext.png" style="margin-right: -8px;" width="230" /></td>
                <td style="text-align:left;margin-left: 0px;"><img src="{{config('app.base_url')}}img/sqsqbannertext.png" style="margin-left: -8px;" width="230" /></td>
            </tr>
            <tr>
                <td style="text-align:right;margin-right: 0px;"><span onclick="gotoPage('home');"
                          onmouseover="$(this).addClass('white-link-hover');"
                          onmouseout="$(this).removeClass('white-link-hover')">work</span><img class="square" src="{{config('app.base_url')}}img/square.png" /><span onclick="gotoPage('about');" onmouseover="$(this).addClass('white-link-hover')" onmouseout="$(this).removeClass('white-link-hover')">about</span></td>
                <td style="text-align:left;margin-left: 0px;"><span  onclick="gotoPage('contact');" onmouseover="$(this).addClass('white-link-hover')"
                          onmouseout="$(this).removeClass('white-link-hover')">contact</span><img class="square" src="{{config('app.base_url')}}img/square.png" /><span onclick="gotoPage('merch');" onmouseover="$(this).addClass('white-link-hover')" onmouseout="$(this).removeClass('white-link-hover')">merch</span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="hidden-xs col-sm-12 hidden-md hidden-lg header-block">
        <div class="row logo-menu-row">
            <span onclick="login();" class="go-top"><img src="{{config('app.base_url')}}img/sqsqboxlesslogo.png" /></span>
        </div>
        <span onclick="gotoPage('home');" onmouseover="$(this).addClass('white-link-hover');"
              onmouseout="$(this).removeClass('white-link-hover')">work</span>
        <img class="square" src="{{config('app.base_url')}}img/square.png" />
        <span onclick="gotoPage('about');" onmouseover="$(this).addClass('white-link-hover')"
              onmouseout="$(this).removeClass('white-link-hover')">about</span>
        <img class="square" src="{{config('app.base_url')}}img/square.png" />
        <span onclick="gotoPage('contact');" onmouseover="$(this).addClass('white-link-hover')"
              onmouseout="$(this).removeClass('white-link-hover')
                                                    ">contact</span>
        <img class="square" src="{{config('app.base_url')}}img/square.png" />
        <span onclick="gotoPage('merch');" onmouseover="$(this).addClass('white-link-hover')"
              onmouseout="$(this).removeClass('white-link-hover')">merch</span>
    </div>
    <div class="col-xs-12 hidden-sm hidden-md hidden-lg header-block">
        <div class="row" class="logo-menu-row">
            <span onclick="login();" class="go-top">&nbsp;&nbsp;&nbsp;&nbsp;<img src="{{config('app.base_url')}}img/sqsqboxlesslogo.png" /></span>
        </div>
        <table class="logo-menu-table">
            <tbody>
            <tr>
                <td class="logo-menu-table-left">
                    <span class="white-link" onclick="gotoPage('home');" onmouseover="$(this).addClass('white-link-hover');"
                          onmouseout="$(this).removeClass('white-link-hover')">work</span>
                </td>
                <td class="square-vertical logo-menu-table-center"><img src="{{config('app.base_url')}}img/square.png"
                    /></td>
                <td class="logo-menu-table-right">
                    <span class="white-link" onclick="gotoPage('about');" onmouseover="$(this).addClass('white-link-hover')"
                          onmouseout="$(this).removeClass('white-link-hover')">about</span>
                </td>
            </tr>
            <tr>
                <td class="logo-menu-table-left">
                    <span class="white-link" onclick="gotoPage('contact');" onmouseover="$(this).addClass('white-link-hover')"
                          onmouseout="$(this).removeClass('white-link-hover')
                                                    ">contact</span>
                </td>
                <td class="square-vertical logo-menu-table-center"><img src="{{config('app.base_url')}}img/square.png" /></td>
                <td class="logo-menu-table-right">
                    <span class="white-link" onclick="gotoPage('merch');" onmouseover="$(this).addClass('white-link-hover')"
                          onmouseout="$(this).removeClass('white-link-hover')">merch</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    @if (Request::is('/') || Request::is('home'))
        <div class="hidden-xs hidden-sm col-md-12 col-lg-12 header-block" style="margin-bottom: 0px !important;">
            <div class="row logo-menu-row">
                <div style="margin-bottom: -10px !important;">Trips and things</div>
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

        $(document).ready( function()
        {
            // On page load and on resize we check some aspects of the page to ensure responsiveness is correct
            addEvent(window, "resize", handleResizeEvent);
            // Calculate the apsect ratio now, so that it is correct on page load
            calcAspectRatio();
            // Also ensure that the About text panel is at least as high as the image panel
            calcAboutTextPanelHeight();

            // Check if we are running the fish tank animation and, if so, kick it off
            bodymovinPanel = document.getElementById('bodymovin');
            if (bodymovinPanel) {
                var animData = {
                    wrapper: bodymovinPanel,
                    animType: 'svg',
                    loop: true,
                    prerender: true,
                    autoplay: true,
                    path: '{{config('app.base_url')}}animation/fishTank.json'
                };
                var anim = bodymovin.loadAnimation(animData);
            }

            // Check if we are running the diver animation and, if so, kick it off
            bodymovinDiverPanel = document.getElementById('bodymovinDiver');
            if (bodymovinDiverPanel) {
                var diverAnimData = {
                    wrapper: bodymovinDiverPanel,
                    animType: 'svg',
                    loop: true,
                    prerender: true,
                    autoplay: true,
                    path: '{{config('app.base_url')}}animation/diver.json'
                };
                var diverAnim = bodymovin.loadAnimation(diverAnimData);
            }

            // Check if we are running the diver animation and, if so, kick it off
            bodymovinDiverVerticalPanel = document.getElementById('bodymovinDiverVertical');
            if (bodymovinDiverVerticalPanel) {
                var diverVerticalAnimData = {
                    wrapper: bodymovinDiverVerticalPanel,
                    animType: 'svg',
                    loop: true,
                    prerender: true,
                    autoplay: true,
                    path: '{{config('app.base_url')}}animation/diver.json'
                };
                var diverVerticalAnim = bodymovin.loadAnimation(diverVerticalAnimData);
            }
        });

        // Bodymovin hand animation functions to go top
        var handAnims = [];
        function createBodymovinHand(elem)
        {
            var handAnimData = {
                wrapper: elem,
                animType: 'svg',
                loop: true,
                prerender: true,
                autoplay: false,
                path: '{{config('app.base_url')}}animation/goToTop.json'
            };
            return bodymovin.loadAnimation(handAnimData);
        }
        function startBodymovinHand(index)
        {
            if (handAnims[index]) {
                handAnims[index].play();
            }
        }
        function stopBodymovinHand(index)
        {
            if (handAnims[index]) {
                handAnims[index].stop();
            }
        }
    </script>
@endsection

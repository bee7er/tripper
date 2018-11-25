@extends('layouts.app')
@section('title') home @parent @endsection

@section('content')

    <div style="margin: 10px;">

        @if($loggedIn)
            <div style="color: white;">{{ $user->username }} logged in</div>
        @endif

        @if(count($tree)>0)
            <div class="row-container">
                <div class="row" style="text-align: left;">
                    @foreach($tree as $twig)
                        <div>{{ $twig->line }}</div>
                    @endforeach
                </div>
            </div>
            <div class="go-top" onclick="scrollToAnchor('top');">
                <div id="goTopHand-work" class="bodymovin-hand" onmouseover="startBodymovinHand(WORK);"
                     onmouseout="stopBodymovinHand(WORK);">
                </div>
            </div>
        @endif

    </div>

    <div id="home">&nbsp;</div>
    @if(count($resources)>0)
        <div class="row-container">
            <div class="row">
                @foreach($resources as $resource)
                    <div onclick="document.location='{{url($resource->name .'')}}';">
                        <img id="{!! $resource->id !!}" class="work-image col-xs-12 col-sm-6 col-md-6 col-lg-4"
                             onmouseover="this.src='{!! url('img/thumbs/'.$resource->hover) !!}'"
                             onmouseout="this.src='{!! url('img/thumbs/'.$resource->thumb) !!}'"
                             src="{!! url('img/thumbs/'.$resource->thumb) !!}" title="" alt="{!!
                             $resource->name !!}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="go-top" onclick="scrollToAnchor('top');">
            <div id="goTopHand-work" class="bodymovin-hand" onmouseover="startBodymovinHand(WORK);"
                 onmouseout="stopBodymovinHand(WORK);">
            </div>
        </div>
    @endif

    <div class="row fish-tank-row-container" style="padding:0;">
        <div id="bodymovin" class="bodymovin-fishtank col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
    </div>

    <div id="about" class="panel-title">about</div>
    <div id="about-row-container" class="row about-row-container" style="padding:0;">
        <table class="hidden-xs hidden-sm col-md-6 col-lg-6 about-table" style="width: 100%;">
            <tr>
                <td style="width:50%;vertical-align: top;border-right: 10px solid #000;">
                    <div id="bodymovinDiver" class="bodymovin-diver"></div>
                </td>
                <td style="width:50%;vertical-align: top;border-left: 10px solid #000;">
                    @include('partials.about-text')
                </td>
            </tr>
        </table>
        <div class="col-xs-12 col-sm-12 hidden-md hidden-lg about-left-vertical">
            <div id="bodymovinDiverVertical" class="bodymovin-diver"></div>
        </div>
        <div class="col-xs-12 col-sm-12 hidden-md hidden-lg about-right-vertical">
            @include('partials.about-text')
        </div>
    </div>
    <div class="go-top" onclick="scrollToAnchor('top');">
        <div id="goTopHand-about" class="bodymovin-hand" onmouseover="startBodymovinHand(ABOUT);"
             onmouseout="stopBodymovinHand(ABOUT);">
        </div>
    </div>

    @if($loggedIn && count($notices)>0)
        <div id="press" class="panel-title">press</div>
        <div>
            <div class="row press-row-container press-adjust-div">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="press-adjust-ul">
                        @foreach($notices as $notice)
                            @if($notice->url)
                                <li><a href="{!! url($notice->url) !!}" class="">{!! $notice->notice !!}</a></li>
                            @else
                                <li>{!! $notice->notice !!}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="go-top" onclick="scrollToAnchor('top');">
            <div id="goTopHand-press" class="bodymovin-hand" onmouseover="startBodymovinHand(PRESS);"
                 onmouseout="stopBodymovinHand(PRESS);">
            </div>
        </div>
    @endif

    <div id="contact" class="panel-title">contact</div>
    <div class="row contact-row-container">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p>Have a project in mind, or just want to say hi?</p>
            <p>I’d love to hear from you!</p>
            <p>
                <a href="javascript: mail2('contact','russ','etheridge','com')"><img class="col-xs-12 col-sm-12 col-md-12 col-lg-12" src="img/emailNumImage.png" title=""></a>
            </p>
            <p class="center-text">Follow me!</p>
            <p class="center-text"><a target="_blank" href="https://dribbble.com/russ_ether"><img src="img/social/dribble.png" class="social-icon" title="Share on dribble" /></a><a target="_blank" href="https://www.facebook.com/russether.animation"><img src="img/social/facebook.png" class="social-icon" title="Share on facebook" /></a><a target="_blank" href="https://www.instagram.com/russ_ether/"><img src="img/social/instagram.png" class="social-icon" title="Share on instagram" /></a><br><a target="_blank" href="https://www.linkedin.com/in/russether"><img src="img/social/linkedin.png" class="social-icon" title="Share on linkedin" /></a><a target="_blank" href="https://twitter.com/russ_ether"><img src="img/social/twitter.png" class="social-icon" title="Share on twitter" /></a><a target="_blank" href="https://vimeo.com/russether"><img src="img/social/vimeo.png" class="social-icon" title="Share on vimeo" /></a></p>
        </div>
    </div>
    <div class="go-top" onclick="scrollToAnchor('top');">
        <div id="goTopHand-contact" class="bodymovin-hand" onmouseover="startBodymovinHand(CONTACT);"
             onmouseout="stopBodymovinHand(CONTACT);">
        </div>
    </div>

    <div id="merch" class="panel-title">merch</div>
    <div class="row merch-row-container">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p><img class="work-image col-xs-12 col-sm-12 col-md-12 col-lg-12" src="img/stills/blocks.jpg" title=""></p>
            <p>Coming soon, and not just a few blocks! Follow me on your favourite social media site for updates.</p>
        </div>
    </div>
    <div class="go-top" onclick="scrollToAnchor('top');">
        <div id="goTopHand-merch" class="bodymovin-hand" onmouseover="startBodymovinHand(MERCH);"
             onmouseout="stopBodymovinHand(MERCH);">
        </div>
    </div>
    @if(count($resources)>0)
        {{-- Preload images --}}
        <div style="visibility: hidden;">
            @foreach($resources as $resource)
                <img src="{!! url('img/thumbs/'.$resource->thumb) !!}" class="hidden-preload">
                <img src="{!! url('img/thumbs/'.$resource->hover) !!}" class="hidden-preload">
            @endforeach
        </div>
    @endif

@endsection

@section('page-scripts')
    <script type="text/javascript">
        var WORK = 0;
        var ABOUT  = 1;
        var CONTACT = 2;
        var MERCH = 3;
        var PRESS = 4;
        $(document).ready( function()
        {
            // Setup the goto top hands and store them in an array
            handAnims[WORK] = createBodymovinHand(document.getElementById('goTopHand-work'));
            handAnims[ABOUT] = createBodymovinHand(document.getElementById('goTopHand-about'));
            handAnims[CONTACT] = createBodymovinHand(document.getElementById('goTopHand-contact'));
            handAnims[PRESS] = createBodymovinHand(document.getElementById('goTopHand-press'));
            handAnims[MERCH] = createBodymovinHand(document.getElementById('goTopHand-merch'));
        });
    </script>
@endsection

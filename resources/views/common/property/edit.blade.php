@extends('layouts.front')
@section('title', trans('common.'.$page))
@section('extra-style')
<link href="/assets/css/property_style.css" rel="stylesheet">
@endsection
@section('content')
<div id="page-content">
            <div class="addProperty-sec Edit-Main-Sec">
                    <div class="mainInnerSec tblRow">
                        <div class="addProperty-left-sec tblCol">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="profileStatus">
                                        <div class="progress-bar" data-percent="{{isset($property) ? $property->getEditCompleted() : 0}}" data-duration="1000" ></div>
                                    </div>
                                </div>
                            </div>
                            <ul>
                                @php
                                    $links = [
                                        'basic_info' => ['Basic Information', 'document'],
                                        'description' => ['Property Description', 'signs'],
                                        'location' => ['Property Location', 'map-pin-signs'],
                                        'pricing' => ['Pricing & Measurements', 'tag'],
                                        'features' => ['Property Features', 'favorite'],
                                        'views' => ['Property Views', 'medical'],
                                        'proximity' => ['In Proximity', 'holidays'],
                                        'gallery' => ['Gallery', 'gallery'],
                                        'choose_agent' => ['Choose Real Estate Agent', 'user-1'],
                                    ];
                                @endphp
                                @foreach($links as $link => $linkDetails)
                                    <li class="{{($page == $link) ? 'active' : ''}} {{(isset($property) && $property->getEditCompleted($link)) ? 'checkedStep' : ''}}">
                                        <a href="{{isset($property) ? (SITE_LANG.'/property/'.$property->id.'/edit/'.$link ) : 'javascript:void(0)'}}">
                                            <i class="glyph-icon flaticon-{{$linkDetails[1]}}"></i> {{$linkDetails[0]}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="addProperty-right-sec tblCol">
                            <form action="/property/update_property" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="property_id" value="{{isset($property) ? base64_encode($property->id) : ''}}">
                                <input type="hidden" name="edit" value="{{base64_encode($page)}}">
                                @include('common.property.'. $page)
                            </form>
                        </div>
                    </div>

            </div>

</div>
@endsection
@section('extra_scripts')
    <script type="text/javascript" src="/assets/js/property-common.js"></script>
    <script src="/assets/js/jQuery-plugin-progressbar.js"></script>
    <script>
        if ($('#mark_as_sold').prop('checked'))
        {
            $('#soldPriceContainer').removeClass('hide');
        }
        $(".progress-bar").loading();
        $(".dropdown-menu li a").click(function () {
            var selText = $(this).text();
            var imgSource = $(this).find('img').attr('src');
            var img = '<img src="' + imgSource + '"/>';
            $(this).parents('.btn-group').find('.dropdown-toggle').html(img + ' ' + selText + ' <span><i class="glyph-icon flaticon-arrow-down-sign-to-navigate"></i></span>');
        });
    </script>
    @yield('inner_extra_scripts')
@endsection

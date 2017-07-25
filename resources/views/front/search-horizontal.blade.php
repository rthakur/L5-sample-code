@php
 if (Auth::check()) {
     $propertyHistory = App\Helpers\CommonHelper::propertyHistory();
 }
 $enterLocationPlaceholder = str_replace("\'",'',trans('common.EnterLocation'));
@endphp
<div class="row search-hz">
    <div class="col-sm-12 col-lg-12">
        <div id="nav-menu">
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                  <li class="map-search-box">
                      <i class="fa fa-map-marker"></i>
                      <input type="text" id="address-map" class="address-map-input" placeholder="{{ $enterLocationPlaceholder }}" value="{{isset($address) ? $address : ''}}">
                      @if(Request::segment(2) != 'buy')
                          <button type="button" class="btn btn-default zoom-map-property" style="display:none;">×</button>
                      @endif
                  </li>
                  <li>
                    <div class="btn-group search-type-box" data-toggle="buttons">
                      <label class="btn btn-primary search-type {{isset($search_type_property) ? $search_type_property: ''}}" data-href="{{SITE_LANG}}/buy/property" id="search-type-property" >
                        <i class="glyph-icon flaticon-signs"></i>
                        List
                      </label>
                      <label class="btn btn-primary search-type {{isset($search_type_gallery) ? $search_type_gallery : ''}}" data-href="{{SITE_LANG}}/gallery" id="search-type-gallery">
                        <i class="glyph-icon flaticon-menu-grid" aria-hidden="true"></i> Grid
                      </label>
                      @if(!isset($dontShowObjectCount))
                      <label class="btn btn-primary search-type {{isset($search_type_map) ? $search_type_map : ''}}" data-href="{{SITE_LANG}}" id="search-type-map">
                        <i class="glyph-icon flaticon-map"></i> Map
                      </label>
                      @endif
                    </div>
                  </li>
                  <li>
                      <button type="button" class="btn btn-sm btn-warning search-type-submit">Search</button>
                  </li>
                </ul>

                <ul class="nav navbar-nav hide">
                    <li class="map-search-box">
                        <i class="glyph-icon flaticon-signs-2"></i>
                        <input type="text" id="address-map" class="address-map-input" placeholder="{{ $enterLocationPlaceholder }}" value="{{isset($address) ? $address : ''}}">
                        @if(Request::segment(2) != 'buy')
                            <button type="button" class="btn btn-default zoom-map-property" style="display:none;">×</button>
                        @endif
                    </li>

                    <li class="dropdown icon-nav">
                        <a href="#" class="search-div dropdown-toggle" data-modal="type" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyph-icon flaticon-home home-icon nav-icon"></i>
				            <span class="filter-count"></span>
				            <br>
                            <span class="modal-name">{{ trans('common.PropertyType') }}</span>
				            <span class="fa fa-angle-down"></span>
                            <input name="type" type="hidden" class="search-field">
                        </a>
                    </li>

                    <li class="dropdown icon-nav">
                        <a href="#" class="search-div dropdown-toggle" data-modal="price" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyph-icon flaticon-tag nav-icon price-tag"></i>
				            <span class="filter-count"></span>
                            <br>
                            <span class="modal-name">{{ trans('common.Price') }}</span>
                            <span class="fa fa-angle-down"></span>
                            <input name="price" type="hidden" class="search-field">
                        </a>
                    </li>

                    <li class="dropdown icon-nav">
                        <a href="#" class="search-div dropdown-toggle" data-modal="size" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyph-icon flaticon-up-arrow resize-icon nav-icon"></i>
    				        <span class="filter-count"></span>
    				        <br>
    				        <span class="modal-name">{{ trans('common.Size') }}</span>
                            <span class="fa fa-angle-down"></span>
                            <input name="size" type="hidden" class="search-field">
                        </a>
                    </li>

                    <li class="dropdown icon-nav">
                        <a href="#" class="search-div dropdown-toggle" data-modal="views" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyph-icon flaticon-medical eye-icon nav-icon"></i>
    				        <span class="filter-count"></span>
    				        <br>
                            <span class="modal-name">{{ trans('common.Views') }}</span>
                            <span class="fa fa-angle-down"></span>
                            <input name="views" type="hidden" class="search-field">
                        </a>
                    </li>

                    <li class="dropdown icon-nav">
                        <a href="#" class="search-div dropdown-toggle" data-modal="proximities" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyph-icon flaticon-map map-icon nav-icon"></i>
                            <span class="filter-count"></span>
                            <br>
                            <span class="modal-name">{{ trans('common.Proximities') }}</span>
                            <span class="fa fa-angle-down"></span>
                            <input name="proximities" type="hidden" class="search-field">
                        </a>
                    </li>

                    <li class="dropdown icon-nav">
                        <a href="#" class="search-div dropdown-toggle" data-modal="features" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="glyph-icon flaticon-favorite feature-icon nav-icon"></i>
    				        <span class="filter-count"></span>
                            <br>
                            <span class="modal-name">{{ trans('common.Features') }}</span>
                            <span class="fa fa-angle-down"></span>
                            <input name="features" type="hidden" class="search-field">
                        </a>
                    </li>

                    <li>
                        <button type="submit" class="btn btn-default btn-sm search-btn" data-href="{{SITE_LANG}}/buy/property">{{ trans('common.SearchList') }} <i class="glyph-icon flaticon-share" aria-hidden="true"></i></button>
                    </li>

                    <li>
                        <button type="button" class="btn btn-info btn-sm search-btn" data-href="{{SITE_LANG}}/gallery">{{ trans('common.GalleryList') }} <i class="glyph-icon flaticon-menu-grid" aria-hidden="true"></i> </button>
                    </li>

                    @if(!isset($dontShowObjectCount))
                        <li>
                            <button type="button" class="btn btn-success btn-sm search-btn" data-href="{{SITE_LANG}}"> {{ trans('common.MAPView') }} <i class="fa fa-globe " aria-hidden="true"></i></button>
                        </li>
                    @endif

                    <li class="clearBtn">
                        <button type="button" class="btn  btn-sm btn-danger clear-all-filters-btn"> {{ trans('common.ClearFilters') }} <i class="fa fa-trash-o"></i></button>
                    </li>
                </ul>

                <ul class="nav navbar-nav pull-right right-nav">
                    @php /* if(Auth::check() && Auth::user()->checkAllowToAddProperty())
                    <li>
                    <a href="{{SITE_LANG}}/property/submit" class="btn btn-sm btn-default"><i class="fa fa-plus"></i><span class="text">{{ trans('common.AddYourProperty') }}</span></a>
                    </li>
                    endif */
                    @endphp

                    @if(isset($view_type))
                        <li>
                            <button type="button"  id="share-btn" data-text="{{ trans('common.Share') }}" data-name="share" class="btn btn-sm btn-warning show-right-container">{{ trans('common.Share') }} <i class="glyph-icon flaticon-share" aria-hidden="true"></i></button>
                        </li>
                    @endif

                    @if(Auth::check() && count($propertyHistory))
                        <li>
                            <button type="button" data-text="{{ trans('common.History') }}" data-name="history" class="btn btn-sm btn-default show-right-container">{{ trans('common.History') }}</button>
                        </li>
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="home-right-container">
    <button type="button" class="close close-lg detail-container-close">&times;</button>
    <h2 class="title"></h2>
    <div class="row" id="propertyHistoryContainer">

        @if(isset($propertyHistory))
            @foreach($propertyHistory as $history)
                @if(isset($history->texts))
                    <div class="col-sm-12">
                        <div class="last-viewed-property-info detail-view-prop">
                            <a href="{{$history->detailPageURL()}}">
                                <h4 style="color:#5B5F67;">{{$history->texts->langSubject()}}</h4>
                            </a>
                            <p style="color:#C7CDC4;">{{$history->langAddress()}}</p>
                            <a href="{{$history->detailPageURL()}}"><img src="{{$history->main_image_url}}" style="width:300px" /></a>
                            <span class="tag price">{{$history->originalPrice()}} </span>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
    <div class="row" id="shareLinkContainer" data-url="{{URL(SITE_LANG.'/s')}}">
        <div class="socialfloat">
            <a class="fbtn share facebook" target="_new" data-url="http://www.facebook.com/sharer/sharer.php?u=" href="http://www.facebook.com/sharer/sharer.php?u={{URL('/')}}"><i class="fa fa-facebook"></i> {{ trans('common.ShareOnFacebook') }}</a>
            <a class="fbtn share instagram" target="_new" data-url='https://plus.google.com/share?url=' href="http://www.facebook.com/sharer/sharer.php?u={{URL('/')}}"><i class="fa fa-google-plus"></i> {{ trans('common.ShareOnGoogle') }}</a>
            <a class="fbtn share twitter" target="_new"  data-url="https://twitter.com/intent/tweet?url=" href="https://twitter.com/intent/tweet?url={{URL('/')}}"><i class="fa fa-twitter"></i> {{ trans('common.ShareOnTwitter') }} </a>
            <a class="fbtn share copylink tooltip-custom" data-url="#"><i class="fa fa-link"></i><div class="tooltiptext">{{trans('common.copied')}}</div> {{ trans('common.CopyLink') }}</a>
        </div>
    </div>
</div>

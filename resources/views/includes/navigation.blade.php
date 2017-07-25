@php
  use App\Models\Language;
  use App\Models\Currency;
  $currentUrl = Request::segment(2);

  $enterLocationPlaceholder = str_replace("\'",'',trans('common.EnterLocation'));

  if(\Session::has('address'))
    $addressAutocomplete = \Session::get('address');

@endphp


<div class="navigation {{ !isset($with_search_bar) ? 'without-search-barheader' : '' }}">
    <div class="secondary-navigation">
        <div class="container container-nav sticky-nav">
            <div class="navbar-brand nav" id="brand">
                <a href="{{SITE_LANG}}" style="cursor:pointer;"><img src="/assets/img/logo.png" alt="MIPARO"></a>
            </div>


            <div class="user-icon visible-xs">
                  <div class="btn-group drop-nav">
                    @if(Auth::check())
                      <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <i class="glyph-icon flaticon-social-1"></i>
                      </button>
                    @else
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa flaticon-login-button"></i>
                    </button>
                    @endif
                      <ul class="dropdown-menu">
                          @if(!Auth::check())

                              <li class="headerBtn">
                                  <a href="{{SITE_LANG}}/register" class="">
                                      <i class="glyph-icon flaticon-register"></i>{{ trans('common.Register') }}
                                  </a>
                              </li>

                              <li class="headerBtn">
                                  <a href="{{SITE_LANG}}/login">
                                      <i class="glyph-icon flaticon-login-button"></i>{{ trans('common.Login') }}
                                  </a>
                              </li>

                          @else

                              @if(Auth::user()->role->id != '1'  && Session::has('auth_access'))
                                  <li>
                                      <a style="color:red;" href="{{SITE_LANG}}/auth/accessaccount/{{Session::get('auth_access')}}">
                                          <b>{{ trans('common.BackToAdmin') }}</b>
                                      </a>
                                  </li>
                              @endif

                              <li class="headerBtn"><a href="{{SITE_LANG}}/account/profile" class="">{{ trans('common.Welcome')}}<strong> {{Auth::user()->name}}</strong></a></li>
                              <li class="headerBtn log-out-btn"><a href="{{SITE_LANG}}/logout"><i class="flaticon-power-button"></i>{{ trans('common.SignOut') }}</a></li>
                          @endif
                      </ul>
                </div>
            </div>

            <!--mobile nav-->


                <div class="user-area">
                    <div class="actions">
                        <div class="mbTgl">
                          <div id="nav-icon1">
                              <span></span>
                              <span></span>
                              <span></span>
                          </div>
                        </div>
                        <header class="navbar mpTopOptions" id="top" role="banner">
                            <!--<div class="navbar-header">
                                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                                    <span class="sr-only">{{ trans('common.ToggleNavigation') }}</span>
                                    <div id="nav-icon1">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </button>
                            </div>-->


                              <nav class="collapse navbar-collapse in bs-navbar-collapse navbar-right navTopSection" role="navigation">
                                  <ul class="nav navbar-nav">

                                      @if(!Auth::check())

                                          <li class="headerBtn">
                                              <a href="{{SITE_LANG}}/register" class="">
                                                  <i class="glyph-icon flaticon-register"></i>{{ trans('common.Register') }}
                                              </a>
                                          </li>

                                          <li class="headerBtn">
                                              <a href="{{SITE_LANG}}/login">
                                                  <i class="glyph-icon flaticon-login-button"></i>{{ trans('common.Login') }}
                                              </a>
                                          </li>

                                      @else

                                          @if(Auth::user()->role->id != '1'  && Session::has('auth_access'))
                                              <li class="headerBtn">
                                                  <a style="color:red;" href="{{SITE_LANG}}/auth/accessaccount/{{Session::get('auth_access')}}">
                                                      <b>{{ trans('common.BackToAdmin') }}</b>
                                                  </a>
                                              </li>
                                          @endif

                                          <li class="headerBtn welcome">{{ trans('common.Welcome') }}</li>
                                          <li class="headerBtn"><a href="{{SITE_LANG}}/account/profile" class="promoted"><strong>{{Auth::user()->name}}</strong></a></li>
                                          <li class="headerBtn log-out-btn"><a href="{{SITE_LANG}}/logout"><i class="flaticon-power-button"></i>{{ trans('common.SignOut') }}</a></li>
                                      @endif
                                  </ul>
                              </nav><!-- /.navbar collapse-->
                              @if(Auth::check())
                              @php
                                $currentUrl = Request::segment(3);
                              @endphp
                              <!-- sidebar -->
                                  <section id="sidebar" class="visible-xs">
                                      <aside>
                                      <ul class="sidebar-navigation">
                                        @if( in_array(Auth::user()->role_id,['2','3']))
                                            <li @if($currentUrl == "profile") class="active" @endif><a href="{{SITE_LANG}}/account/profile"><i class="glyph-icon flaticon-user-1"></i><span>{{ trans('common.Profile') }}</span></a></li>
                                        @endif

                                       <!-- Agent Sidebar -->
                                        @if(Auth::user()->role_id == '2')
                                         @include('profile.sidebar.regular_user')

                                        <!-- Agent Sidebar -->
                                        @elseif(Auth::user()->role_id == '3')
                                          @include('profile.sidebar.agent')

                                       <!-- Agency Sidebar -->
                                        @elseif(Auth::user()->role_id == '4')
                                          @include('profile.sidebar.agency')
                                        @endif
                                       </ul>
                                      </aside>
                                  </section><!-- /#sidebar -->
                              <!-- end Sidebar -->
                              @endif
                              <nav class="collapse navbar-collapse bs-navbar-collapse in " role="navigation">

                                  @php
                                      $appLangg = App::getLocale();
                                      $selectedLang = Language::getLangByCountryCode($appLangg);
                                      App::setLocale('en');
                                  @endphp

                                  <ul class="nav navbar-nav">
                                      <li class="has-child contact-nav btn-group app-currency">

                                          <a href="javascript:void(0)">
                                              <img id="imgBtnSel" src="/assets/img/flags/{{$appLangg}}.png" alt="" class="img-thumbnail icon-medium">
                                              <span id="lanBtnSel">{{ trans('common.'. $selectedLang) }}</span>
                                          </a>

                                          <ul class="child-navigation">

                                              @foreach(Language::getAllLanguages() as $name => $shortName)
                                                  <li>
                                                      <a id="btnIta" href="?setlang={{$shortName}}" class="language">
                                                          <img id="imgBtnIta" src="/assets/img/flags/{{$shortName}}.png" alt="" class="img-thumbnail icon-small"> 
                                                          <span id="lanBtnlIta">{{trans('common.'. $name)}}</span>
                                                      </a>
                                                  </li>
                                              @endforeach

                                              @php App::setLocale($appLangg); @endphp

                                          </ul>
                                          <span class="fa fa-angle-down"></span>
                                      </li>
                                  </ul>
                              </nav>
                              @php $currentCurrency = App\Helpers\CommonHelper::getAppCurrency(false, true); @endphp

                              <div class="btn-group app-currency custom-currency">
                                  <select class="currency" id="btnIta" onchange="window.location.href= this.value;">
                                      @foreach(Currency::allCurrenciesWithCountries() as  $currency)
                                          @if( $currency->currency != '(none)' )
                                              <option value="{{SITE_LANG}}/set_curr/{{$currency->id}}" data-currency="{{$currentCurrency->id}}" @if($currentCurrency && $currentCurrency->id == $currency->id) selected @endif>
                                                  <span id="lanBtnlIta">{{$currency->symbol .' '. $currency->currency}}</span>
                                              </option>
                                          @endif
                                      @endforeach
                                  </select>
                              </div>

                              <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right in" role="navigation">
                                  <ul class="nav navbar-nav">
                                      <li class="has-child contact-nav btn-group app-currency"><a href="javascript:void(0)">{{ trans('common.Contact') }}</a>
                                          <ul class="child-navigation">
                                              <li><a href="{{SITE_LANG}}/contact">{{ trans('common.Contact') }} MiParo</a></li>
                                              <li><a href="{{SITE_LANG}}/contact/investment">{{ trans('common.Investment') }}</a></li>
                                              <li><a href="{{SITE_LANG}}/team">{{ trans('common.OurTeam') }} </a></li>
                                              <li><a href="{{SITE_LANG}}/about">{{ trans('common.About') }} MiParo</a></li>
                                          </ul>
                                          <span class="fa fa-angle-down"></span>
                                      </li>
                                  </ul>
                              </nav>
                        </header>
                    </div>
                </div>

                <div class="main-nav custom-nav">
                    <ul class="nav navbar-nav">
                        <li><h3>{{ trans('common.SearchProperties') }}</h3></li>
                        <li class="map-search-box">
                            <i class="fa fa-map-marker"></i>
                            <input type="text" id="address-map" class="address-map-input" placeholder="{{ $enterLocationPlaceholder }}" value="{{isset($addressAutocomplete) ? $addressAutocomplete : ''}}">

                                <button type="button" class="btn btn-default zoom-map-property" style="display:none;">×</button>
                        </li>
                        <li class="sub-menu-parent subNewMenu visible-xs">
                            @if(isset($view_type))
                                <button type="button" id="share-btn" data-text="{{ trans('common.Share') }}" data-name="share" class="btn btn-default share-btn navShare-btn ">
                                    <i class="glyph-icon flaticon-share"></i> <span>{{ trans('common.Share') }}</span>
                                </button>
                            @endif
                            <ul class="sub-menu socialfloat" id="shareLinkContainer" data-url="{{URL(SITE_LANG.'/s')}}">
                                <li>
                                    <a class="fbtn share facebook" target="_new" data-url="http://www.facebook.com/sharer/sharer.php?u=" href="http://www.facebook.com/sharer/sharer.php?u={{URL('/')}}"><i class="fa fa-facebook"></i> {{ trans('common.ShareOnFacebook') }}</a>
                                </li>
                                <li>
                                    <a class="fbtn share instagram" target="_new" data-url='https://plus.google.com/share?url=' href="http://www.plus.google.com/sharer/sharer.php?u={{URL('/')}}"><i class="fa fa-google-plus"></i> {{ trans('common.ShareOnGoogle') }}</a>
                                </li>
                                <li>
                                    <a class="fbtn share twitter" target="_new"  data-url="https://twitter.com/intent/tweet?url=" href="https://twitter.com/intent/tweet?url={{URL('/')}}"><i class="fa fa-twitter"></i> {{ trans('common.ShareOnTwitter') }} </a>
                                </li>
                                <li>
                                    <a class="fbtn share copylink tooltip-custom"><i class="fa fa-link"></i><div class="tooltiptext">{{trans('common.copied')}}</div> {{ trans('common.CopyLink') }}</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="btn-group search-type-box search-box-New" data-toggle="buttons">
                                <label class="btn btn-primary search-type {{isset($search_type_map) ? $search_type_map : ''}}" data-href="{{SITE_LANG}}" id="search-type-map">
                                    <i class="glyph-icon flaticon-map"></i> <span>{{ trans('common.Map') }}</span>
                                </label>
                                <label class="btn btn-primary search-type {{isset($search_type_property) ? $search_type_property: ''}}" data-href="{{SITE_LANG}}/buy/property" id="search-type-property" >
                                    <i class="glyph-icon flaticon-signs"></i> <span>{{ trans('common.List') }}</span>
                                </label>
                                <label class="btn btn-primary search-type {{isset($search_type_gallery) ? $search_type_gallery : ''}}" data-href="{{SITE_LANG}}/gallery" id="search-type-gallery">
                                    <i class="glyph-icon flaticon-menu-grid" aria-hidden="true"></i> <span>{{ trans('common.Gallery') }}</span>
                                </label>




                            </div>
                        </li>

                        @if(isset($view_type))
                            <li class="sub-menu-parent hidden-xs">
                                    <button type="button" id="share-btn" data-text="{{ trans('common.Share') }}" data-name="share" class="btn btn-default share-btn navShare-btn ">
                                        <i class="glyph-icon flaticon-share"></i> <span>{{ trans('common.Share') }}</span>
                                    </button>
                                <ul class="sub-menu socialfloat" id="shareLinkContainer" data-url="{{URL(SITE_LANG.'/s')}}">
                                    <li>
                                        <a class="fbtn share facebook" target="_new" data-url="http://www.facebook.com/sharer/sharer.php?u=" href="http://www.facebook.com/sharer/sharer.php?u={{URL('/')}}"><i class="fa fa-facebook"></i> {{ trans('common.ShareOnFacebook') }}</a>
                                    </li>
                                    <li>
                                        <a class="fbtn share instagram" target="_new" data-url='https://plus.google.com/share?url=' href="http://www.plus.google.com/sharer/sharer.php?u={{URL('/')}}"><i class="fa fa-google-plus"></i> {{ trans('common.ShareOnGoogle') }}</a>
                                    </li>
                                    <li>
                                        <a class="fbtn share twitter" target="_new"  data-url="https://twitter.com/intent/tweet?url=" href="https://twitter.com/intent/tweet?url={{URL('/')}}"><i class="fa fa-twitter"></i> {{ trans('common.ShareOnTwitter') }} </a>
                                    </li>
                                    <li>
                                        <a class="fbtn share copylink tooltip-custom"><i class="fa fa-link"></i><div class="tooltiptext">{{trans('common.copied')}}</div> {{ trans('common.CopyLink') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </div>



            </div>
            <!--mobile nav -->
            @if(isset($with_search_bar) && isset($view_type) && ($view_type == 'List' || $view_type == 'Map' || $view_type == 'Gallery'))
                    <div class="filterMbSection visible-xs">
                        <div class="filterTgl">
              <a id="showMblFilter">
                <i class="glyph-icon flaticon-interface-1" aria-hidden="true"></i>
                 {{trans('common.Filter')}}  <span class="filterCheck"><i class="glyph-icon flaticon-interface" aria-hidden="true"></i></span> </a></div>
                        <div class="propertyType"></div>
                    </div>
            @endif

        </div>
    </div><!-- /.navigation -->


    <!--Share Link container  -->
    <div class="home-right-container">
        <button type="button" class="close close-lg detail-container-close">&times;</button>
        <h2 class="title"></h2>
        <div class="row property-history-container" id="propertyHistoryContainer">

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
    </div>
    <!--End Share Link Container  -->

<!-- Page Footer -->
@php $recentProperties = App\Models\Property::recentViewProperties(2); @endphp
<footer id="page-footer">

    <div class="inner">
        @if(Request::segment(2) != '')
        <aside id="footer-main">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <article>
                            <h3>{{ trans('common.AboutUs') }}</h3>
                            <p>{{ trans('common.AboutUsLongText') }}</p>
                            <hr>
                            <a href="{{SITE_LANG}}/about" class="link-arrow">{{ trans('common.ReadMore') }}</a>
                        </article>
                    </div><!-- /.col-sm-3 -->
                    <div class="col-sm-3">
                        @if($recentProperties->count())
                        <article>
                            <h3>{{ trans('common.RecentProperties') }}</h3>
                            @foreach($recentProperties as $property)
                              <div class="property small">
                                  <a href="{{$property->detailPageURL()}}">
                                      <div class="property-image">
                                          <img alt="" src="{{ $property->main_image_url }}">
                                      </div>
                                  </a>
                                  <div class="info">
                                      <a href="{{$property->detailPageURL()}}"><h4>{{$property->texts->langSubject()}}</h4></a>
                                      <figure>{{$property->address}} </figure>
                                      <div class="tag price">{{$property->exchangePrice()}}</div>
                                  </div>
                              </div><!-- /.property -->
                            @endforeach
                        </article>
                        @endif
                    </div><!-- /.col-sm-3 -->
                    <div class="col-sm-3">
                        <article>
                            <h3>{{ trans('common.Contact') }}</h3>
                            <address>
                                <strong>MiParo</strong><br>
                                {!! trans('common.MiParoAdreessStreetAddress') !!}<br>
                                {{ trans('common.MiParoAddressCityAndCountry') }}
                            </address>
                            {{Config::get('sitesettings.phone_number')}}<br>
                            <a href="#">{{Config::get('sitesettings.support_email')}}</a>
                        </article>
                    </div><!-- /.col-sm-3 -->
                    <div class="col-sm-3">
                        <article>
                            <h3>{{ trans('common.UsefulLinks') }}</h3>
                            <ul class="list-unstyled list-links">
                                <li><a href="{{SITE_LANG}}/{{trans('seolinks.buy')}}/property">{{ trans('common.AllProperties') }}</a></li>
                                <li><a href="{{SITE_LANG}}/privacy-policy" target="_blank">{{ trans('common.PrivacyPolicy') }}</a></li>
                                @if(!Auth::check())
                                <li><a href="{{SITE_LANG}}/register">{{ trans('common.LoginAndRegisterAccount') }}</a></li>
                                @endif
                                <li><a href="{{SITE_LANG}}/faq" target="_blank">{{ trans('common.FAQ') }}</a></li>
                                <li><a href="http://help.miparo.com" target="_blank">{{ trans('common.HelpCenter') }}</a></li>
                                <li><a href="{{SITE_LANG}}/terms-conditions" target="_blank">{{ trans('common.TermsAndConditions') }}</a></li>
                            </ul>
                        </article>
                    </div><!-- /.col-sm-3 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </aside><!-- /#footer-main -->
        <aside id="footer-thumbnails" class="footer-thumbnails"></aside><!-- /#footer-thumbnails -->
        @endif

    </div><!-- /.inner -->
      <aside id="footer-copyright">
      <div class="inner">
          <div class="container">
              <span>{{ trans('common.Copyright') }} &copy; {{date('Y')}}. {{trans('common.AllRightsReserved')}}.</span>
          </div>
          </div>
      </aside>

</footer>

<!-- end Page Footer -->

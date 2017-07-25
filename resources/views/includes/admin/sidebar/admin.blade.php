<li>
    <a href="{{SITE_LANG}}/admin/manageuser" class="{{$currentUrl == 'manageuser' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Users') }}</span><span class="sidebar-short-name"><b>{{trans('common.MU')}}</b></span></a>
</li>
<li>
  <a href="{{SITE_LANG}}/admin/agency" class="{{$currentUrl == 'agency' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Agencies') }}</span><span class="sidebar-short-name"><b>{{trans('common.MA')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/property" class="{{$currentUrl == 'property' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Properties') }}</span><span class="sidebar-short-name"><b>{{trans('common.MP')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/sync-request/requests" class="{{$currentUrl == 'sync-request' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.SyncRequests') }}</span><span class="sidebar-short-name"><b>{{trans('common.SR')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/sync-property-request/requests" class="{{$currentUrl == 'sync-property-request' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.SyncPropertyRequests') }}</span><span class="sidebar-short-name"><b>{{trans('common.SPR')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/message" class="{{$currentUrl == 'message' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Messages') }} </span><span class="sidebar-short-name"><b>{{trans('common.MM')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/contact/message" class="@if(Request::segment(4) =='message'){{$currentUrl == 'contact' ? 'active' : ''}}@endif"><span class="sidebar-full-name">{{ trans('common.Contact').' '. trans('common.Messages') }}</span><span class="sidebar-short-name"><b>{{trans('common.CM')}}</b></span>@if(Auth::user()->getUnreadMessages()->count())<sup class="msg-notification"><b>{{ Auth::user()->getUnreadMessages()->count() }}</b></sup>@endif</a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/zones" class="{{$currentUrl == 'zones' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Zones') }}</span> <span class="sidebar-short-name"><b>{{trans('common.MZ')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/campaign" class="{{$currentUrl == 'campaign' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Campaign') }}</span><span class="sidebar-short-name"><b>{{trans('common.MC')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/va/country" target="_blank"><span class="sidebar-full-name">{{ trans('common.Country').' '. trans('common.Link') }}</span><span class="sidebar-short-name"><b>{{trans('common.CL')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/va/manage" target="_blank"><span class="sidebar-full-name">{{ trans('common.Virtual').' '.trans('common.Assistants').' '. trans('common.Link') }}</span><span class="sidebar-short-name"><b>{{trans('common.VAL')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/currencies" class="{{$currentUrl == 'currencies' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.Currencies') }} </span><span class="sidebar-short-name"><b>{{trans('common.MC')}}</b></span></a>
</li>
<li>
    <a href="{{SITE_LANG}}/admin/intervals" class="{{$currentUrl == 'intervals' ? 'active' : ''}}"><span class="sidebar-full-name">{{ trans('common.Manage').' '. trans('common.PriceIntervals') }} </span><span class="sidebar-short-name"><b>{{trans('common.MPI')}}</b></span></a>
</li>

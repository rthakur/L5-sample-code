@php
  $currentUrl = Request::segment(3);
@endphp
<!-- sidebar -->
<div class="col-md-3 col-sm-4 hidden-xs sidebar-col-container">
    <section id="sidebar">
        <header><h3>{{ trans('common.Account') }}</h3></header>
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
</div><!-- /.col-md-3 -->
<!-- end Sidebar -->

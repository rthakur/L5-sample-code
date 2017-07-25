@php $currentUrl = Request::segment(3); @endphp
<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="{{SITE_LANG}}" class="logo"><b><span class="sidebar-full-name"> MIPARO </span><span class="sidebar-short-name"> M </span></b></a>
        </li>
        @if(Auth::user()->role_id == 1)
          @include('includes.admin.sidebar.admin')
        @else  
          @include('includes.admin.sidebar.virtual_assistant')
        @endif
    </ul>
</div>
<!-- /#sidebar-wrapper -->

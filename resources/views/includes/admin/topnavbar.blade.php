<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    
    <ul class="nav navbar-nav navbar-right">
        <li><a href="/">{{ trans('common.Profile') }}</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <img id="imgBtnSel" src="/assets/img/flags/{{App::getLocale()}}.png" alt="" class="img-thumbnail icon-medium">  
            <span id="lanBtnSel">{{ trans('common.'.App\Models\Language::getLangByCountryCode( App::getLocale() ) ) }}</span></button>
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            @foreach(App\Models\Language::getAllLanguages() as $lang => $shortName)
             <li><a id="btnIta" href="?setlang={{$shortName}}" class="language"> <img id="imgBtnIta" src="/assets/img/flags/{{$shortName}}.png" alt="" class="img-thumbnail icon-small"> <span id="lanBtnlIta">{{  trans('common.'.$lang) }}</span></a></li>
            @endforeach
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}} <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">{{ trans('common.MyAccount') }}</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="{{SITE_LANG}}/logout">{{ trans('common.Logout') }}</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
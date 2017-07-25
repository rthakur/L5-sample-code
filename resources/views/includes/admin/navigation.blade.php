@php $currentUrl = Request::segment(1) @endphp
    <div class="navigation">
        <div class="container">
            <header class="navbar" id="top" role="banner">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <div id="nav-icon1">
                          <span></span>
                          <span></span>
                          <span></span>
                        </div>
                    </button


                    <div class="navbar-brand nav" id="brand">
                        <a href="/"><img src="/assets/img/logo.png" alt="MIPARO"></a>
                    </div>
                </div>
                <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">{{ trans('common.ManageAgency') }}</a></li>
                        <li><a href="{{SITE_LANG}}/logout">Logout</a></li>
                    </ul>
                </nav><!-- /.navbar collapse-->
            </header><!-- /.navbar -->
        </div><!-- /.container -->
    </div><!-- /.navigation -->

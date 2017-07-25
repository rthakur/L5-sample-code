@if(Auth::user()->role_id == 4)
    <div class="btn-main">
        <a href="{{SITE_LANG}}/property/plans">
            <span>
                <i class="fa fa-shopping-cart"></i>
            </span>
            {{trans('common.ChangePlan')}}
        </a>
    </div>
@endif
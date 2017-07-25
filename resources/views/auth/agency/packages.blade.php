@php
    use App\Helpers\ViewsHelper;

    $allPackages = App\Models\Package::where('view_status', '1')->get();

    $fields = [
        'AgencyProfile' => 'flaticon-user-1',
        'AgentProfiles' => 'flaticon-user-1',
        'Logotype' => 'flaticon-technology',
        'AgentAccounts' => 'flaticon-people',
        'Objects' => 'flaticon-direction',
        'Statistics' => 'flaticon-graphic',
        'APIAccess' => 'flaticon-tool',
        'HomepageSynchronization' => 'fa-television'
    ];

    $selectedPackage = !empty($selectedPackage) ? $selectedPackage : null;

@endphp

<section id="select-package" @if(!empty($campaignKey)) class="hidden" @endif>

    @if(empty($noProfessionalPlan))
        @if(!empty($selectedPackage))
            <header><h1><i class="glyph-icon flaticon-trophy"></i>{{ trans('common.Subscription') }}</h1></header>
        @else
            <header>
                <h3>
                    {{ trans('common.SelectaPackage') }}
                    <i class="fa fa-question-circle tool-tip"  data-toggle="tooltip" data-placement="right" title="{{ trans('common.ChangePackageLaterMessage') }}"></i>
                </h3>
            </header>
        @endif
    @endif
    <div class="subscriptionTable">
        @if(!empty($selectedPackage))
        @include('includes.notification')
        @endif
        <div class="packageInfo">
            <h3>{{trans('common.SubscriptionHead')}}</h3>
            <p>{{trans('common.SubscriptionHeadContent')}}</p>
        </div>


        <table class="table customTable-sec customTable-list">
            <tr>
                <th class="no-border"></th>
            </tr>
            <tr>
                <td></td>
            </tr>
            @foreach($fields as $field => $iconClass)
                <tr>
                    <td>
                        <i class="{{$field == 'HomepageSynchronization' ? 'fa' : 'glyph-icon'}} {{$iconClass}}"></i>
                        <span> {{ trans('common.'. $field) }} <i class="glyph-icon flaticon-information-button tool-tip"  data-toggle="tooltip" data-placement="right" title="{{ trans('explainers.package_explainers_'. $field) }}"></i></span>
                    </td>
                </tr>
            @endforeach

        </table>

        @foreach($allPackages as $k => $package)
            <table class="table customTable-sec customTable-feature {{ (ViewsHelper::selectPackageOne($selectedPackage, $package, $k)) ? 'customTable-feature1' : ''}}">

                <tr>
                    <th class="no-border">
                        <div class="radio">
                            <input type="radio" id="checkbox-{{$k}}" data-href="{{SITE_LANG}}/payment/update/{{$package->id}}" data-name="{{trans('common.'.$package->name)}}" name="package_select" value="{{$package->id}}" {{ViewsHelper::selectPackageOne($selectedPackage ?: null, $package, $k) ? 'checked' : ''}}>
                            <label for="checkbox-{{$k}}">{{$package->name}}</label>
                        </div>
                    </th>
                </tr>

                <tr>
                    <td class="price">${{$package->price}}/{{trans('common.Month')}}</td>
                </tr>

                @php $innerFields = ['agency_profiles', 'agent_profiles', 'logotype', 'agent_accounts', 'objects', 'generic_statistics', 'api_access', 'synchronization']; @endphp

                @foreach($innerFields as $field)
                    <tr>
                        {!! $package->getFieldHTML($field) !!}
                    </tr>
                @endforeach

            </table>

        @endforeach

    </div>

</section>

@if(!empty($selectedPackage))

<div class="faq-sec">

    <h3 class="subTitle">{{trans('common.FrequentlyAskedQuestions')}}</h3>
    <div class="row">

        @php $totalRows = 6; @endphp

        @for($row = 1; $row <= $totalRows; $row++)

            @if($row == 1 || ($row == ceil($totalRows/2) + 1))
                <div class="col-md-6">
                    <div class="faqContent">
                        <ul>
            @endif
                        <li>
                            <h4>{{ trans('faq.packages_heading_'. $row) }}</h4>
                            <p>{{ trans('faq.packages_text_'. $row) }}</p>
                        </li>

            @if($row == ceil($totalRows/2) || $row == $totalRows)
                @php $nextCount = true; @endphp
                        </ul>
                    </div>
                </div>
            @endif

        @endfor
    </div>
</div>

@endif

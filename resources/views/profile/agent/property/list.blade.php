<div class="table-responsive">
    <div class="row">
        @foreach($properties as $property)
            <div class="col-md-4 property-list-cols">
                <div class="thumbnail property-list-wrapper">
                    
                    <a href="{{$property->detailPageURL()}}" class="property-list-thumb-img">
                        <img alt="" src="{{ $property->getMainImageUrl() }}" style="width:100%">
                    </a>
                    
                    <div class="caption">
                        <header class="property-list-name">
                            <p><a href="{{$property->detailPageURL()}}">{{ $property->langSubject() }}</a></p>
                            
                            @if($property->agent)
                                <span class="pull-right"><i class="fa fa-user"></i> {{$property->agent_id != Auth::id() ? $property->agent->name : trans('common.Owned')}}</span>
                            @endif
                            
                            <div class="date-added">{{ $property->createdDate() }}</div>
                        </header>
                        
                        <hr>
                        
                        <dl class="property-thumbs-btns clearfix">
                            @if(Auth::user()->checkPropertyAccess($property->id))
                                <dt class="first-child-btn">
                                    <a href="{{SITE_LANG}}/property/{{$property->id}}/edit" class="edit" @if(Request::segment('5') == 'new') target="_new" @endif>
                                        <i class="fa fa-pencil"></i>{{ trans('common.Edit') }}
                                    </a>
                                </dt>
                            @endif
                            
                            @if(isset($new) && $new == 'confirm_sold')
                                <dt class="center-child-btn">
                                    <a href="{{SITE_LANG}}/account/agent/{{$property->id}}/sold/confirm" class="edit">
                                        <i class="fa fa-check-circle"></i>{{ trans('viewproperty.Sold') }}
                                    </a>|
                                    <a href="{{SITE_LANG}}/account/agent/{{$property->id}}/keep/confirm" class="edit">{{ trans('viewproperty.Keep') }}</a>
                                </dt>
                            @endif
                  
                            @if( Auth::user()->role_id == 3 && Auth::user()->package_id == 2)
                                <dt class="center-child-btn"><a href="{{SITE_LANG}}/account/agency/property-details/{{$property->id}}">
                                    <i class="fa fa fa-bar-chart-o"></i> {{ trans('common.ViewStatistics') }}</a>
                                </dt>
                            @endif
                  
                            @if(Auth::user()->allowToDeleteProperty($property))
                                <dt class="last-child-btn">
                                    <a href="{{SITE_LANG}}/property/delete/{{$property->id}}" class="delete-btn" data-item="{{trans('common.property')}}">
                                        <i class="delete fa fa-trash-o"></i> {{ trans('common.Delete') }}
                                    </a>
                                </dt>
                            @endif
                        </dl>
                        
                        <div class="row">
                            <div class="col-xs-6 total-views">
                                <label>{{ trans('common.TotalViews') }}</label> 
                                <b>{{$property->stats->count()}}</b>
                            </div>
                  
                            <div class="col-xs-6">
                                <span class="property-price pull-right">{{ $property->originalPrice() }}</span>
                            </div>
                        </div><!--row-->
                    </div><!--caption-->
                </div><!--list-wrapper-->
            </div><!--col-sm-4-->
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="center">
        {{ $properties->links() }}
    </div><!-- /.center-->
</div><!-- /.table-responsive -->    
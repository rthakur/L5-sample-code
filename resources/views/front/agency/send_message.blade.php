<div class="col-md-7 col-md-offset-2 agency-contact-div" data-message="{{trans('common.ThankYouContactMessage')}}">
    <h3>{{ trans('common.SendUsaMessage') }}</h3>
    <div class="agent-form">
        <form role="form" id="form-contact-agent" method="post" action="/agency/sendmessage" class="clearfix">

            {{ csrf_field() }}
            <input type="hidden" value="{{(isset($agency)) ? $agency->id : ''}}" name="agency_id">

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="form-contact-agent-name">{{ trans('common.YourName') }}<em>*</em></label>
                        <input type="text" class="form-control contact-field" id="form-contact-agent-name" name="sender_name">
                    </div><!-- /.form-group -->
                </div><!-- /.col-md-6 -->

                <div class="col-md-6">
                    <div class="form-group">
                      <label for="form-contact-agent-email">{{ trans('common.YourEmail') }}<em>*</em></label>
                      <input type="email" class="form-control contact-field" id="form-contact-agent-email" name="sender_email">
                    </div><!-- /.form-group -->
                </div><!-- /.col-md-6 -->

            </div><!-- /.row -->

            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="form-contact-agent-message">{{ trans('common.YourMessage') }}<em>*</em></label>
                        <textarea class="form-control contact-field" id="form-contact-agent-message" rows="5" name="message"></textarea>
                    </div><!-- /.form-group -->
                </div><!-- /.col-md-12 -->

            </div><!-- /.row -->

            {!! Recaptcha::render() !!}

            <br><br>

            <small style="float:left;">
                <sup><span class="">*</span></sup>
                <span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span>
            </small>

            <div class="form-group clearfix">
                <button type="submit" class="btn pull-right btn-default" data-text ="{{ trans('common.SendaMessage') }}" id="form-contact-agent-submit">{{ trans('common.SendaMessage') }}</button>
            </div><!-- /.form-group -->

        </form><!-- /#form-contact -->
    </div><!-- /.rating-form -->

    <div class="alert alert-success agency-contact-errors hidden"><strong></strong></div>

</div>

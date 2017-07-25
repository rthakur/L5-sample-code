@extends('layouts.admin')
@section('content')
@php use App\Models\Language; @endphp

<div class="row">
  <div class="col-lg-12">
      <h1>{{ trans('common.Agencies') }}</h1>
      @if($agencies->count())
      <div class="form-group">
        <label for="submit-title"> {{ trans('common.SelectLanguage') }}</label><br>
        @foreach(Language::getAllLanguages() as $shortName)
          <input type="radio" data-title="{{ isset($property->texts->{'subject_'.$shortName})? $property->texts->{'subject_'.$shortName} : ''}}"
          data-description="{{ isset($property->texts->{'description_'.$shortName})? $property->texts->{'description_'.$shortName} : ''}}"  
          name="lang" value="{{$shortName}}" class="dont-style select-language" @if($shortName == str_replace('/','',SITE_LANG))  checked="" @endif> 
          <img id="imgBtnIta" src="/assets/img/flags/{{$shortName}}.png" alt=""> 
        @endforeach
      </div>
      <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th width="30%"> {{trans('common.AgencyDetails')}}</th>
                  <th> {{trans('common.Firstcontact1')}}</th>
                  <th> {{trans('common.Firstcontact2')}}</th>
                  <th> {{trans('common.FirstContract1')}}</th>
                  <th> {{trans('common.FirstContract2')}}</th>
                </tr>
              </thead>
                <tbody class="user">
                  @foreach($agencies as $agency)
                  <tr>
                    <td>
                      @if($agency->logo)
                      <img src="{{$agency->logo}}" height="50px"/>
                      @endif
                      <p><b>{{trans('common.PublicName')}} :</b> {{$agency->public_name}}</p>
                      <p><b>{{trans('common.Email')}} :</b> {{$agency->info_email}}</p>
                    </td>
                    <td>
                      <a href="javascript:void(0)" data-toggle="modal" class="view-email-content" data-content-type="contact_1" data-id="{{$agency->id}}" data-target="#viewEmailContent">
                        <i class="fa fa-eye" aria-hidden="true"></i> {{trans('common.EmailContent')}}
                      </a>
                      <br>
                      <a href="javascript:void(0)" class="send-email-content" data-content-type="contact_1" data-id="{{$agency->id}}"><i class="fa fa-envelope" aria-hidden="true"></i> {{trans('common.SendEmail')}}</a>
                    </td>
                    <td>
                      <a href="javascript:void(0)" data-toggle="modal" class="view-email-content" data-content-type="contact_2" data-id="{{$agency->id}}" data-target="#viewEmailContent">
                        <i class="fa fa-eye" aria-hidden="true"></i> {{trans('common.EmailContent')}}
                      </a>
                      <br>
                      <a href="javascript:void(0)" class="send-email-content" data-content-type="contact_2" data-id="{{$agency->id}}"><i class="fa fa-envelope" aria-hidden="true"></i> {{trans('common.SendEmail')}}</a>
                    </td>
                    <td>
                      <a href="javascript:void(0)" data-toggle="modal" class="view-email-content" data-content-type="contract_1" data-id="{{$agency->id}}" data-target="#viewEmailContent">
                        <i class="fa fa-eye" aria-hidden="true"></i> {{trans('common.EmailContent')}}
                      </a>
                      <br>
                      <a href="javascript:void(0)" class="send-email-content" data-content-type="contract_1" data-id="{{$agency->id}}"><i class="fa fa-envelope" aria-hidden="true"></i> {{trans('common.SendEmail')}}</a>
                    </td>
                    <td>
                      <a href="javascript:void(0)" data-toggle="modal" class="view-email-content" data-content-type="contract_2" data-id="{{$agency->id}}" data-target="#viewEmailContent">
                        <i class="fa fa-eye" aria-hidden="true"></i> {{trans('common.EmailContent')}}
                      </a>
                      <br>
                      <a href="javascript:void(0)" class="send-email-content" data-content-type="contract_2" data-id="{{$agency->id}}"><i class="fa fa-envelope" aria-hidden="true"></i> {{trans('common.SendEmail')}}</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
      </div>
      @else
      <hr>
      @include('includes.no_result')
      @endif
  </div>
  @include('admin.virtual_assistant.agency.modal')
</div>
@endsection
@section('extra_scripts')
  <script>
    $(document).ready(function(){
      $('.view-email-content').click(function(){
        var Language = $('.select-language:checked').val();
        $('#viewEmailContent').modal('show');
        $('#emailContentContainer').html('<h2>Loading ...</h2>');
        data = { 'agencyId' : $(this).data('id'), 'contentType' : $(this).data('content-type'), 'lang' :Language };
        $.ajax({
          url: '/admin/agency/email-content',
          data: data,
          success: function(response){
            $('#emailContentContainer').html(response);
          }
        });
      });
      
      $('.send-email-content').click(function(){
        var _this = $(this);
        var Language = $('.select-language:checked').val();
        data = { 'agencyId' : _this.data('id'), 'contentType' : _this.data('content-type'), 'lang' :Language  };
        $.ajax({
          url: '/admin/agency/check-email-send',
          data: data,
          success: function(response){
              successFunc(response, _this);
          }
        });
      });
    });
    
    function successFunc(response, _this)
    {
      if(response['errors'] === undefined)
          swal({
           title:SendEmailConfirmPermission.replace(':attribute',questionMark),
           type: 'warning',
           confirmButtonText: confirmButton,
           showCancelButton: true,
           cancelButtonText: Cancel,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
          }).then(function () {
              _this.text('Sending ...');
              sendMail(_this);          
            }, function (dismiss) {
              if (dismiss === 'cancel') 
                swal(Cancelled,'','error')
            }
          );
      else
      {
        swal({
          title: SendEmailConfirmPermission,
          text: AlreadySent,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: confirmButton,
          cancelButtonText: Cancel,
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false
        }).then(function () {
            _this.text('Sending ...');
            sendMail(_this);          
          }, function (dismiss) {
            if (dismiss === 'cancel') {
              swal(Cancelled,'','error')
            }
          }
        );
      }
    }
    
    function sendMail(_this)
    {
      $.ajax({
        url: '/admin/agency/email-send',
        data: data,
        success: function(response){
          _this.addClass('success');
          _this.html('<i class="fa fa-check-square-o" aria-hidden="true"></i> Sent');
          swal(Success,MailSent,'success');
        }
      });
    }
  </script>
@endsection
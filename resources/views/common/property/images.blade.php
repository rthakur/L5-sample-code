
<!-- <div class="gallery-top property-main-img-conainer">
    <div class="image-options text-center">{{trans('common.MainImage')}}</div>
        <img id="main-img" src="{{ $property->main_image_url }}">
</div> -->
@if(!empty($property->main_image_url))
<div class="row">
  <div class="gallery-top col-md-12 property-main-img-conainer">
      <div>
        <div class="image-options main-image-text  text-center">{{trans('common.MainImage')}}</div>
        <img id="main-img" alt="" src="{{ $property->main_image_url }}">
      </div>
  </div>
</div>
@endif
<figure class="note"><strong>{{ trans('viewproperty.Hint') }}:</strong> {{ trans('viewproperty.UploadAllImages') }}</figure>
<div class="gallery-botm row">
        @foreach($property->propertyImagesWithMainImage as $image)
        <div class="col-sm-3 col-xs-3 gallery-col">
            <div class="property-image">
              <div class="pull-right image-options delIconStyle">
                @if($image->main_image != 1)
                  <a class="image-options-icons image-options-set-main-image" data-id="{{$image->id}}" data-property-id="{{$property->id}}">{{trans('common.SetMainImage')}}</a>
                @endif
                <i class="glyph-icon flaticon-dustbin image-options-icons image-options-icon-delete text-danger delStyle" data-id="{{$image->id}}" data-property-id="{{$property->id}}"></i>
              </div>
                <img alt="" src="{{ $image->s3_url}}">
            </div>
        </div><!-- /.col-md-3 -->
        @endforeach
        <div class="col-sm-3 col-xs-3 gallery-col add-more-btn">
            <div class="addMoreImg">
                <i class="glyph-icon flaticon-picture"></i>
                <h5>{{trans('common.Add').' '.($property->propertyImagesWithMainImage->count() ? trans('common.More').' ' : ' ').trans('common.Images')}}</h5>
                <input type="file" id="file-upload" name="images[]" class="addImgFld" multiple="true" accept="image/jpeg,image/png">
            </div>
        </div><!-- /.col-md-3 -->
        <div class="col-md-3 ajax-load" style="display:none">
                <center><img src="/assets/img/ajax-loader.svg"></center>
        </div><!-- /.col-md-3 -->
        <!--
        <li class="">
            <div class="addMoreImg">
                <i class="glyph-icon flaticon-picture"></i>
                <h5>{{trans('common.Add').$property->propertyImagesWithMainImage->count() ? trans('common.More') : ''.trans('common.Images')}}</h5>
                <input type="file" id="file-upload" name="images[]" class="addImgFld" multiple="true" accept="image/jpeg,image/png">
            </div>
        </li> -->

        <!-- <li class="ajax-load" style="display:none">
            <div class="addMoreImg">
                <center><img src="/assets/img/ajax-loader.svg"></center>
            </div>
        </li> -->
</div>

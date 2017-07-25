<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th width="20%">Title</th>
        <th width="50%">Map View Link</th>
        <th width="30%">Copy Link</th>
      </tr>
    </thead>
      <tbody class="user">
        @foreach($bookmarklinks as $bookmarklink)
        <tr>
          <th>{{$bookmarklink->title}}</th>
          <td>
              {{substr(url(SITE_LANG).$bookmarklink->link, 0, 60)}}
              @if(strlen($bookmarklink->link) > 60)...@endif
          </td>
          <td>
            <textarea class="hidden" id="map-view-link">{{url(SITE_LANG).$bookmarklink->link}}</textarea>
            <a class="copy-btn" data-clipboard-target="map-view-link">
              <i class="fa fa-files-o"></i> Map view
            </a> |
            <textarea class="hidden" id="list-view-link">{{url(SITE_LANG .'/buy/property').$bookmarklink->link}}</textarea>
            <a class="copy-btn" data-clipboard-target="list-view-link">
              <i class="fa fa-files-o"></i> List view
            </a> |
            <textarea class="hidden" id="gallery-view-link">{{url(SITE_LANG .'/gallery').$bookmarklink->link}}</textarea>
            <a class="copy-btn" data-clipboard-target="gallery-view-link">
              <i class="fa fa-files-o"></i> Gallery view
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
  </table>
  {{ $bookmarklinks->links() }}
</div>

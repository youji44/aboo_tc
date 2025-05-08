<div class="card">
    <div class="card-body">
        <h3 class="text-uppercase">{{$topic->topic_title}}</h3>
        <hr>
        <div>
            {!! $topic->description !!}
        </div>
        <hr>
        @if($topic->attach_files && is_array($files = json_decode($topic->attach_files)))
            <div class="form-group">
                <h6 class="col-form-label font-weight-bold">ATTACHMENT</h6>
                <ul class="list-unstyled">
                    @foreach($files as $key=>$file)
                        <li class="mb-1">
                            <a class="text-info" href="{{route('file.download')}}?file={{$file}}">
                                <i class="ti-file mr-2"></i>
                                Attached File {{$key+1}}
                                <small class="text-muted ml-2">
                                    (.{{ pathinfo($file, PATHINFO_EXTENSION) }})
                                </small>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
<hr>
<div class="form-group float-right mt-2">
    <button type="button" class="btn btn-danger" data-dismiss="modal"> Close </button>
</div>

<script>
    $(document).ready(function() {
        $('figure.media').has('[data-oembed-url*="youtu"]').each(function() {
            var $iframeContainer = $(this).find('[style*="padding-bottom"]');
            var $iframe = $iframeContainer.find('iframe');
            var $responsiveContainer = $('<div>').css({
                'position': 'relative',
                'padding-bottom': '56.25%',  // 16:9 aspect ratio (9/16 = 0.5625)
                'height': '0',
                'overflow': 'hidden',
                'width': '100%'
            });
            // Move iframe into the new container
            $iframe.appendTo($responsiveContainer).css({
                'position': 'absolute',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%'
            });
            // Replace original figure with the new container
            $(this).replaceWith($responsiveContainer);
        });
    });

</script>

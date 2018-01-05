@if(is_array($feed) && !empty($feed))
    <div class="{{$classes}}">
        @if (!$hideTitle && !empty($post_title))
            <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
        @endif

        <ul id="{{ $sectionID }}" class="rss-feed rss-feed-v2">
            @foreach($feed as $item)
                <li>
                    <a href="{{ $item['link'] }}" class="box box-news box-news-horizontal">
                        <div class="box-content">
                            <h3 class="box-title text-highlight">{{ $item['title'] }}</h3>
                            <time datetime="{{ $item['time_markup'] }}">{{ $item['time_readable'] }} {{ $translations['ago'] }}</time>
                            <p>{{ $item['excerpt'] }}</p>
                            <p><span class="link-item">{{ $translations['readmore'] }}</span></p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@else
    <div class="notice info">
         <i class="pricon pricon-info-o"></i> {{ $translations['noposts'] }}
    </div>
@endif


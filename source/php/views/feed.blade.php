@if(is_array($feed) && !empty($feed))
    <ul id="{{ $sectionID }}" class="social social-feed-v2">
        @foreach($feed as $item)
            <li>
                <a href="{{ $item['link'] }}" class="box box-news box-news-horizontal">
                    <!-- <div class="box-image-container">
                        <img src="https://unsplash.it/200/130/?image=600">
                    </div> -->
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

@else
    <div class="notice info">
         <i class="pricon pricon-info-o"></i> {{ $translations['noposts'] }}
    </div>
@endif


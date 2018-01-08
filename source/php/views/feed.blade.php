@if(is_array($feed) && !empty($feed))
    <div class="{{$classes}}">
        @if (!$hideTitle && !empty($post_title))
            <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
        @endif

        <!-- Functional css -->
        <style scoped>
            .is-hidden .pricon-eye {
                display: none;
            }

            .is-hidden .pricon-eye-hide {
                display: inline-block;
            }
        </style>

        <!-- List -->
        <ul id="{{ $sectionID }}" class="rss-feed rss-feed-v2">
            @foreach($feed as $item)
                <li>
                    <a href="{{ $item['link'] }}" class="box box-news box-news-horizontal">

                        <div class="box-content">
                            <h3 class="box-title text-highlight">
                                {{ $item['title'] }}

                                @if($showVisibilityButton)
                                    <button class="js-mod-rss-toggle-visibility {{ $item['visibilityClass'] }}" data-module-id="{{ $moduleId }}" data-inlay-id="{{ $item['id'] }}"><i class="pricon pricon-eye"></i><i class="pricon pricon-eye-hide"></i></button>
                                @endif
                            </h3>

                            @if(in_array('date', $display))
                            <time datetime="{{ $item['time_markup'] }}">{{ $item['time_markup'] }} ({{ $item['time_readable'] }} {{ $translations['ago'] }})</time>
                            @endif

                            @if(in_array('label', $display))
                            <span class="source strong">
                                @if(in_array('date', $display))
                                    -
                                @endif
                                {{ $item['encloushure']['title'] }}
                            </span>
                            @endif

                            @if(in_array('excerpt', $display))
                                <p>{{ $item['excerpt'] }}</p>
                            @endif

                            @if(in_array('content', $display))
                                <p>{{ $item['content'] }}</p>
                            @endif

                            @if(in_array('readmore', $display))
                            <p><span class="link-item">{{ $translations['readmore'] }}</span></p>
                            @endif

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


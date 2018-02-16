@if(is_array($feed) && !empty($feed))
    <div class="{{$classes}}">
        @if (!$hideTitle && !empty($post_title))
            <h4 class="box-title">{!! apply_filters('the_title', $post_title) !!}</h4>
        @endif

        <!-- Functional css -->
        <style scoped>

            .pricon-eye-hide {
                display: none;
            }

            .is-hidden .pricon-eye {
                display: none;
            }

            .is-hidden .pricon-eye-hide {
                display: inline-block;
            }
        </style>

        <!-- List -->
        <ul id="{{ $sectionID }}" class="c-posts c-posts--rss rss-feed rss-feed-v2">
            @foreach($feed as $item)
                <li class="c-posts__item">
                    <a href="{{ $item['link'] }}" class="c-feed c-feed--rss u-no-decoration u-inherit-color u-content-spacing">

                        <div class="c-feed__header">
                            <h3 class="c-feed__title u-display-inline">
                                {{ $item['title'] }}
                            </h3>
                            <span class="u-text-small u-nowrap u-display-none u-display-inline@md">({{ $item['time_readable'] }} {{ $translations['ago'] }})</span>

                            @if($showVisibilityButton)
                                <button class="o-visibility-toggle c-feed__visibility-toggler js-mod-rss-toggle-visibility {{ $item['visibilityClass'] }}" data-module-id="{{ $moduleId }}" data-inlay-id="{{ $item['id'] }}"><i class="pricon pricon-eye"></i><i class="pricon pricon-eye-hide"></i></button>
                            @endif

                            <div class="c-feed__meta u-text-small">
                                @if(in_array('date', $display))
                                    <b class="c-feed__date">
                                        <time datetime="{{ $item['time_markup'] }}">{{ $item['time_markup'] }} </time>
                                    </b>
                                @endif

                                @if(in_array('label', $display))
                                    <b class="c-feed__source">
                                        {{ $item['encloushure']['title'] }}
                                    </b>
                                @endif
                            </div>
                        </div>

                        <div class="c-feed__body">
                            @if(in_array('excerpt', $display))
                                <p>{{ $item['excerpt'] }}</p>
                            @endif

                            @if(in_array('content', $display))
                                <p>{{ $item['content'] }}</p>
                            @endif
                        </div>

                        <div class="c-feed__footer">
                            @if(in_array('readmore', $display))
                            <span class="c-feed__action btn btn--flat">{{ $translations['readmore'] }}</span>
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


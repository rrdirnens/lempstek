@extends('layout')

@section('content')

    <section class="container mx-auto p-4">
        <div class="flex flex-col items-center">
            <form action="{{ route('search') }}" method="GET" class="h-12 flex justify-center md:w-auto w-full">
                <input type="text" name="search_query" placeholder="Enter keywords" required
                       class="border w-[70%] md:w-80 border-2 border-r-0 border-stone-600 px-4 h-full"
                       value="{{ $search_query ?? '' }}">
                <button type="submit"
                        class="flex justify-center p-2 w-[30%] md:w-32 h-full text-lg bg-rose-200 hover:bg-rose-300 border border-2 border-l-0 border-stone-600">
                    <span class="hidden md:block">Search</span>
                    <img src="../images/icons/solid/search.svg" alt="" class="h-full md:hidden">
                </button>
            </form>
            @if ((!$logged_in && empty($search_query)) || (empty($shows) && empty($movies) && empty($search_query)))
                <div class="flex items-center">
                    <img src="../images/icons/solid/arrow-up-circle.svg" alt="arrow up" class="w-8 mr-4">
                    <p>search for your favorite show or movie here</p>
                </div>
            @endif
        </div>


        @isset($search_results)
            <form action="{{ route('search') }}" method="GET" class="pagination">
                <button type="submit" name="page" value="{{ $search_pagination_current - 1 }}"
                        class="p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current > 1 ? 'bg-rose-200' : 'hidden' }}">
                    < </button>
                        @if ($search_pagination_total > 10)
                            <div class="pagination__warning">
                                <img src="../images/icons/solid/warn.svg" alt="" class="pagination__image">
                                <div class="pagination__message">
                                    Ay, yo, my guy, das a lot of results({{ $search_pagination_total }} pages). You can narrow
                                    it down by adding more keywords.
                                </div>
                            </div>
                            @for ($i = $search_pagination_current - 1; $i < $search_pagination_current + 9; $i++)
                                <button type="submit" name="page" value="{{ $i + 1 }}"
                                        class="md:block p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current == $i + 1 ? 'bg-rose-300' : 'bg-rose-200 hidden' }}">{{ $i + 1 }}</button>
                            @endfor
                            <button type="submit" name="page" value="{{ $search_pagination_current + 10 }}"
                                    class="hidden md:block p-2 w-32 h-full text-lg bg-rose-200 hover:bg-rose-300">...</button>
                        @elseif($search_pagination_total > 1)
                            @for ($i = 0; $i < $search_pagination_total; $i++)
                                <button type="submit" name="page" value="{{ $i + 1 }}"
                                        class="hidden md:block p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current == $i + 1 ? 'bg-rose-300' : 'bg-rose-200' }}">
                                    {{ $i + 1 }}
                                </button>
                            @endfor
                            <button name="page" value="{{ $search_pagination_current }}"
                                    class="md:hidden block p-2 w-32 h-full text-lg hover:bg-rose-300 bg-rose-300">{{ $search_pagination_current }}</button>
                        @endif
                        <button type="submit" name="page" value="{{ $search_pagination_current + 1 }}"
                                class="p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current < $search_pagination_total ? 'bg-rose-200' : 'hidden' }}">></button>
                        <input type="text" name="search_query" class="hidden" value="{{ $search_query }}">
            </form>
        @endisset

        @if (isset($search_results))
            @if (!$logged_in || (empty($shows) && empty($movies)))
                <div class="flex items-center justify-center my-8">
                    <img src="../images/icons/solid/arrow-down-circle.svg" alt="arrow down" class="w-8 mr-4">
                    <p>click/tap "Add to calendar" or click on the name to view it</p>
                </div>
            @endif

            <div class="md:flex">
                <div class="tab-container tab-container--search-results md:hidden">
                    <div class="tab-menu">
                        <ul>
                            @if ($search_results->tv)
                                <li><a href="#" class="tab-a active-a" data-id="tab1">SHOWS</a></li>
                            @endif
                            @if ($search_results->movies)
                                <li><a href="#" class="tab-a" data-id="tab2">MOVIES</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="tab tab-active" data-id="tab1">
                        <div class="search-results mb-4">
                            @if ($search_results->tv)
                                <div class="search-results__shows">
                                    @foreach ($search_results->tv as $item)
                                        <div class="search-result__cover border border-2 border-stone-600 p-2 mb-1 hover:bg-stone-200">
                                            @if (isset($item->poster_path))
                                                <img class="search-result__image max-w-5/10" src="https://image.tmdb.org/t/p/w500/{{ $item->poster_path }}" alt="">
                                            @else
                                                <img class="search-result__image max-w-5/10" src="../images/placeholders/no_postah.jpg" alt="">
                                            @endif
                                            @if (isset($item->in_calendar) && $item->in_calendar)
                                                <a href="{{ route('users.tv.delete', $item->id) }}"
                                                   class="inline-block mb-2 hover:text-rose-500 text-rose-400">REMOVE</a>
                                            @else
                                                <a href="{{ route('users.tv.store', $item->id) }}"
                                                   class="inline-block mb-2 hover:text-rose-400">Add to calendar</a>
                                            @endif
                                            <a href="{{ route('tv.show', $item->id) }}">
                                                <div>
                                                    <strong>{{ $item->name }}</strong>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="tab" data-id="tab2">
                        <div class="search-results mb-4">
                            @if ($search_results->movies)
                                <div class="search-results__movies">
                                    @foreach ($search_results->movies as $item)
                                        <div
                                             class="search-result__cover border border-2 border-stone-600 p-2 mb-1 hover:bg-stone-200">
                                            @if (isset($item->poster_path))
                                                <img class="search-result__image max-w-5/10"
                                                     src="https://image.tmdb.org/t/p/w500/{{ $item->poster_path }}"
                                                     alt="">
                                            @else
                                                <img class="search-result__image max-w-5/10"
                                                     src="../images/placeholders/no_postah.jpg" alt="">
                                            @endif

                                            @if (isset($item->in_calendar) && $item->in_calendar)
                                                <a href="{{ route('users.movie.delete', $item->id) }}" class="inline-block
                                                   mb-2 hover:text-rose-300 text-rose-200">REMOVE</a>
                                            @else
                                                <a href="{{ route('users.movie.store', $item->id) }}" class="inline-block mb-2 hover:text-rose-400">Add to calendar</a>
                                            @endif
                                            <a href="{{ route('movies.show', $item->id) }}">
                                                <div>
                                                    <strong>{{ $item->title }}</strong>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            </div>


            <div class="hidden md:flex justify-center search-results mb-4">
                @if ($search_results->tv)
                    <div class="mr-2 max-w-5/10 w-[50%] search-results__shows">
                        <h1 class="text-2xl text-right font-bold">TV shows</h1>
                        @foreach ($search_results->tv as $item)
                            <div class="search-result__cover border border-2 border-stone-600 p-2 mb-1 hover:bg-stone-200">
                                @if (isset($item->poster_path))
                                    <img class="search-result__image max-w-5/10"
                                         src="https://image.tmdb.org/t/p/w500/{{ $item->poster_path }}" alt="">
                                @else
                                    <img class="search-result__image max-w-5/10"
                                         src="../images/placeholders/no_postah.jpg" alt="">
                                @endif
                                @if (isset($item->in_calendar) && $item->in_calendar)
                                    <a href="{{ route('users.tv.delete', $item->id) }}"
                                       class="inline-block mb-2 hover:text-rose-500 text-rose-400">REMOVE</a>
                                @else
                                    <a href="{{ route('users.tv.store', $item->id) }}"
                                       class="inline-block mb-2 hover:text-rose-400">Add to calendar</a>
                                @endif
                                <a href="{{ route('tv.show', $item->id) }}">
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($search_results->movies)
                    <div class="ml-2 max-w-5/10 w-[50%] search-results__movies">
                        <h1 class="text-2xl font-bold">Movies</h1>
                        @foreach ($search_results->movies as $item)
                            <div class="search-result__cover border border-2 border-stone-600 p-2 mb-1 hover:bg-stone-200">
                                @if (isset($item->poster_path))
                                    <img class="search-result__image max-w-5/10"
                                         src="https://image.tmdb.org/t/p/w500/{{ $item->poster_path }}" alt="">
                                @else
                                    <img class="search-result__image max-w-5/10"
                                         src="../images/placeholders/no_postah.jpg" alt="">
                                @endif

                                @if (isset($item->in_calendar) && $item->in_calendar)
                                    <a href="{{ route('users.movie.delete', $item->id) }}"
                                       class="inline-block mb-2 hover:text-rose-300 text-rose-200">REMOVE</a>
                                @else
                                    <a href="{{ route('users.movie.store', $item->id) }}"
                                       class="inline-block mb-2 hover:text-rose-400">Add to calendar</a>
                                @endif
                                <a href="{{ route('movies.show', $item->id) }}">
                                    <div>
                                        <strong>{{ $item->title }}</strong>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @isset($search_results)
            <form action="{{ route('search') }}" method="GET" class="pagination">
                <button type="submit" name="page" value="{{ $search_pagination_current - 1 }}"
                        class="p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current > 1 ? 'bg-rose-200' : 'hidden' }}">
                    < </button>
                        @if ($search_pagination_total > 10)
                            <div class="pagination__warning">
                                <img src="../images/icons/solid/warn.svg" alt="" class="pagination__image">
                                <div class="pagination__message">
                                    Ay, yo, my guy, das a lot of results({{ $search_pagination_total }} pages). You can narrow
                                    it down by adding more keywords.
                                </div>
                            </div>
                            @for ($i = $search_pagination_current - 1; $i < $search_pagination_current + 9; $i++)
                                <button type="submit" name="page" value="{{ $i + 1 }}"
                                        class="md:block p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current == $i + 1 ? 'bg-rose-300' : 'bg-rose-200 hidden' }}">{{ $i + 1 }}</button>
                            @endfor
                            <button type="submit" name="page" value="{{ $search_pagination_current + 10 }}"
                                    class="hidden md:block p-2 w-32 h-full text-lg bg-rose-200 hover:bg-rose-300">...</button>
                        @elseif($search_pagination_total > 1)
                            @for ($i = 0; $i < $search_pagination_total; $i++)
                                <button type="submit" name="page" value="{{ $i + 1 }}"
                                        class="hidden md:block p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current == $i + 1 ? 'bg-rose-300' : 'bg-rose-200' }}">
                                    {{ $i + 1 }}
                                </button>
                            @endfor
                            <button name="page" value="{{ $search_pagination_current }}"
                                    class="md:hidden block p-2 w-32 h-full text-lg hover:bg-rose-300 bg-rose-300">{{ $search_pagination_current }}</button>
                        @endif
                        <button type="submit" name="page" value="{{ $search_pagination_current + 1 }}"
                                class="p-2 w-32 h-full text-lg hover:bg-rose-300 {{ $search_pagination_current < $search_pagination_total ? 'bg-rose-200' : 'hidden' }}">></button>
                        <input type="text" name="search_query" class="hidden" value="{{ $search_query }}">
            </form>
        @endisset

        <div>{{ isset($search_msg) ? $search_msg : '' }}</div>

    </section>

    @if ($logged_in)
        <section class="container mx-auto p-4">

            <div class="mb-4">
                <h2 class="mb-4 text-2xl font-bold">UPCOMING</h2>
                <div class="upcoming__cover grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    @if (isset($dates) && !$dates->isEmpty())
                        @foreach ($dates as $date => $items)
                            <div
                                 class="col-span-1 m-2 p-2 border border-2 border-stone-600 {{ $items[0]['is_today'] ? 'bg-rose-200' : 'bg-white' }}">
                                <div class="mb-2">{{ $date }} @if ($items[0]['is_today'])
                                        | <span class="font-bold">TODAY</span>
                                    @endif
                                </div>
                                @if ($items[0]['days_left'] >= 0)
                                    <div>
                                        {{ $items[0]['day'] }} (in {{ $items[0]['days_left'] }} days)
                                    </div>
                                @else
                                    <div>
                                        {{ $items[0]['day'] }} ( {{ abs($items[0]['days_left']) }} days ago )
                                    </div>
                                @endif

                                <div class="mt-2">
                                    @foreach ($items as $item)
                                        <div class="font-bold mb-1 hover:bg-stone-200 py-2 px-1">
                                            @if ($item['type'] == 'movie')
                                                <a href="{{ route('movies.show', $item['id']) }}}"
                                                   class="leading-5">{{ $item['name'] }}</a>
                                            @else
                                                <a
                                                   href="{{ route('tv.show', $item['id']) }}">{{ $item['show_name'] }}</a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <br>
                            </div>
                        @endforeach
                    @else
                        <small class="text-rose-400 ml-4">No upcoming premieres</small>
                    @endif
                </div>
            </div>

            <div class="mb-8">
                <h2 class="mb-4 text-2xl font-bold">MOVIES</h2>
                @if (empty($movies))
                    <small class="text-rose-400 ml-4">No movies in your calendar</small>
                @endif
                <div class="flex flex-wrap">
                    @if (isset($movies) && $movies)
                        @foreach ($movies as $movie)
                            <div class="item-button">
                                <div class="item-button__container">
                                    {{ $movie['title'] }}
                                </div>
                                <div class="item-button__overlay">
                                    <a class="item-button__action item-button__action--view"
                                       href="{{ route('movies.show', $movie['id']) }}">View</a>
                                    <div class="item-button__divider">|</div>
                                    <a class="item-button__action item-button__action--remove"
                                       href="{{ route('users.movie.delete', $movie['id']) }}">Remove</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="mb-8">
                <h2 class="mb-4 text-2xl font-bold">SHOWS</h2>
                @if (empty($shows))
                    <small class="text-rose-400 ml-4">No shows in your calendar</small>
                @endif
                <div class="flex flex-wrap">
                    @if (isset($shows) && $shows)
                        @foreach ($shows as $show)
                            <div class="item-button">
                                <div class="item-button__container">
                                    {{ $show['name'] }}
                                </div>
                                <div class="item-button__overlay">
                                    <a class="item-button__action item-button__action--view"
                                       href="{{ route('tv.show', $show['id']) }}">View</a>
                                    <div class="item-button__divider">|</div>
                                    <a class="item-button__action item-button__action--remove"
                                       href="{{ route('users.tv.delete', $show['id']) }}">Remove</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    @endif

@endsection

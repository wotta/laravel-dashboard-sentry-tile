@php /** @var Wotta\SentryTile\Objects\Issue $issue */ @endphp
<x-dashboard-tile :position="$position" :refresh-interval="$refreshIntervalInSeconds">
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="text-3xl leading-none -mt-1">
            {{ $projectName }} @if($title){{ $title }}@endif
        </div>
        <div class="divide-y-2 overflow-auto">
            @foreach($issues as $issue)
                <li class="overflow-hidden py-4 list-none">
                    <div class="grid gap-2">
                        <div class="grid grid-cols-auto-1 gap-2 items-center">
                            <div class="leading-tight min-w-0">
                                <h4 class="truncate text-sm font-bold" title="@if(! $projectName){{ $issue->project()->slug() }}: @endif{{ $issue->title() }}">@if(! $projectName){{ $issue->project()->slug() }}: @endif {{ $issue->title() }}</h4>
                            </div>
                        </div>

                        @if($showMeta)
                            @if (! $issue->meta()->title())
                                <div class="text-xs">
                                    <div class="underline">{{ $issue->meta()->method() }}</div>
                                    {{-- <div class="text-dimmed">{{ $issue->meta()->type() }}</div> --}}
                                    @if ($issue->meta()->value())
                                        <div class="mt-1 text-dimmed">
                                            <span>{{ \Illuminate\Support\Str::limit($issue->meta()->value()) }}</span>
                                        </div>
                                    @endif
                                    <div>{{ $issue->meta()->filename() }}</div>
                                </div>
                            @endif
                        @endif

                        <div class="grid grid-cols-auto-1 gap-2 items-center">
                            <div class="leading-tight min-w-0">
                                <div class="truncate text-xs text-dimmed">
                                    <p class="text-{{ $issue->level() }}">{{ $issue->level() }}</p> -
                                    {{ $issue->status() }} –
                                    <span title="{{ $issue->lastSeen(\Wotta\SentryTile\Objects\Issue::FORMAT) }}">{{ $issue->lastSeen() }}</span> -
                                    <a href="{{ $issue->permalink() }}" class="hover:underline">Permalink</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </div>
    </div>
</x-dashboard-tile>

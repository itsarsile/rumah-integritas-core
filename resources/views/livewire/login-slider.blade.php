@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<div class="hidden lg:flex lg:flex-col lg:flex-1 lg:basis-1/2 lg:pr-4 lg:min-w-[28rem] lg:max-w-[40rem]">
    @if ($slides->isNotEmpty())
        @php $total = $slides->count(); @endphp

        <div class="relative w-full" data-carousel-scope data-carousel-interval="4000">
        <div class="carousel w-full max-w-3xl min-w-[28rem] mx-auto rounded-xl overflow-hidden shadow-xl">
            @foreach ($slides as $index => $slide)
                @php
                    $isExternal = Str::startsWith($slide->image_path, ['http://', 'https://']);
                    // Use a scheme-relative, same-origin path for local files so it works with both HTTP and HTTPS
                    $imageUrl = $isExternal
                        ? $slide->image_path
                        : '/storage/' . ltrim($slide->image_path, '/');
                    $id = 'item' . ($index + 1);
                @endphp
                <div id="{{ $id }}" class="carousel-item w-full relative aspect-[4/5]">
                    <img src="{{ $imageUrl }}" class="absolute inset-0 w-full h-full object-cover bg-neutral-400" alt="Slide {{ $slide->title ?? $index + 1 }}" />

                    @if ($slide->title || $slide->subtitle || $slide->description || ($slide->button_text && $slide->button_url))
                        <div class="absolute inset-x-0 bottom-0 z-10 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-8 text-white space-y-3">
                            @if ($slide->subtitle)
                                <p class="text-sm uppercase tracking-wider text-white/80">{{ $slide->subtitle }}</p>
                            @endif
                            @if ($slide->title)
                                <h3 class="text-2xl font-semibold">{{ $slide->title }}</h3>
                            @endif
                            @if ($slide->description)
                                <p class="max-w-xl text-white/80 leading-relaxed">{{ $slide->description }}</p>
                            @endif
                            @if ($slide->button_text && $slide->button_url)
                                <a href="{{ $slide->button_url }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">{{ $slide->button_text }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @if ($total > 1)
            <div class="absolute inset-x-0 bottom-6 z-20 flex w-full justify-center gap-3">
                @foreach ($slides as $index => $slide)
                    <button
                        type="button"
                        class="h-1.5 w-12 rounded-full bg-white/30 backdrop-blur-sm transition-colors"
                        aria-label="Go to slide {{ $index + 1 }}"
                        data-carousel-go="{{ $index }}"
                    ></button>
                @endforeach
            </div>
        @endif
        </div>
    @else
        <div class="rounded-xl overflow-hidden shadow-xl">
            <div class="relative aspect-[4/5] w-full">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80" class="absolute inset-0 w-full h-full object-cover" alt="Placeholder slide">
            </div>
        </div>
    @endif
</div>

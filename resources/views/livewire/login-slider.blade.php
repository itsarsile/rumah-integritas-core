@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<div class="hidden lg:flex lg:flex-col lg:w-full">
    @if ($slides->isNotEmpty())
        @php $total = $slides->count(); @endphp
        <div class="carousel w-full rounded-xl overflow-hidden shadow-xl">
            @foreach ($slides as $index => $slide)
                @php
                    $prevIndex = ($index === 0) ? $total - 1 : $index - 1;
                    $nextIndex = ($index === $total - 1) ? 0 : $index + 1;
                    $prevSlide = $slides[$prevIndex];
                    $nextSlide = $slides[$nextIndex];
                    $isExternal = Str::startsWith($slide->image_path, ['http://', 'https://']);
                    $imageUrl = $isExternal ? $slide->image_path : Storage::disk('public')->url($slide->image_path);
                @endphp
                <div id="login-slide-{{ $slide->id }}" class="carousel-item relative w-full">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="Slide {{ $slide->title ?? $index + 1 }}" />

                    @if ($slide->title || $slide->subtitle || $slide->description || ($slide->button_text && $slide->button_url))
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-8 text-white space-y-3">
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
                                <a href="{{ $slide->button_url }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                    {{ $slide->button_text }}
                                </a>
                            @endif
                        </div>
                    @endif

                    @if ($total > 1)
                        <div class="absolute flex justify-between items-center transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a href="#login-slide-{{ $prevSlide->id }}" class="btn btn-circle btn-sm bg-white/80 border-none text-base-content hover:bg-primary hover:text-primary-content">❮</a>
                            <a href="#login-slide-{{ $nextSlide->id }}" class="btn btn-circle btn-sm bg-white/80 border-none text-base-content hover:bg-primary hover:text-primary-content">❯</a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($total > 1)
            <div class="flex justify-center gap-2 mt-4">
                @foreach ($slides as $index => $slide)
                    <a href="#login-slide-{{ $slide->id }}" class="w-3 h-3 rounded-full transition-colors duration-300 {{ $index === 0 ? 'bg-primary' : 'bg-base-300 hover:bg-primary/70' }}"></a>
                @endforeach
            </div>
        @endif
    @else
        <div class="rounded-xl overflow-hidden shadow-xl">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80" class="w-full object-cover" alt="Placeholder slide">
        </div>
    @endif
</div>

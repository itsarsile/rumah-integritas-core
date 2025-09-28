@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<div class="hidden lg:flex lg:flex-col lg:w-full">
    @if ($slides->isNotEmpty())
        @php $total = $slides->count(); @endphp

        <div class="glide max-w-4xl rounded-xl overflow-hidden shadow-xl">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    @foreach ($slides as $index => $slide)
                        @php
                            $isExternal = Str::startsWith($slide->image_path, ['http://', 'https://']);
                            $imageUrl = $isExternal ? $slide->image_path : Storage::disk('public')->url($slide->image_path);
                        @endphp
                        <li class="glide__slide max-w-4xl relative">
                            <img src="{{ $imageUrl }}" class="w-full h-[512px] object-cover bg-neutral-400" alt="Slide {{ $slide->title ?? $index + 1 }}" />

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
                                        <a href="{{ $slide->button_url }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">{{ $slide->button_text }}</a>
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($total > 1)
                <div class="glide__arrows" data-glide-el="controls">
                    <button class="glide__arrow glide__arrow--left btn btn-circle btn-sm bg-white/80 border-none text-base-content hover:bg-primary hover:text-primary-content" data-glide-dir="<">❮</button>
                    <button class="glide__arrow glide__arrow--right btn btn-circle btn-sm bg-white/80 border-none text-base-content hover:bg-primary hover:text-primary-content" data-glide-dir=">">❯</button>
                </div>

                <div class="glide__bullets flex justify-center gap-2 mt-4" data-glide-el="controls[nav]">
                    @foreach ($slides as $index => $slide)
                        <button class="glide__bullet w-3 h-3 rounded-full bg-base-300" data-glide-dir="={{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="rounded-xl overflow-hidden shadow-xl">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80" class="w-full object-cover" alt="Placeholder slide">
        </div>
    @endif
</div>

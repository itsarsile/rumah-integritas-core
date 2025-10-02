<?php

namespace App\Livewire\Admin;

use App\Models\LoginSlide;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pengaturan Slider Login')]
class LoginSliderManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected string $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $sortField = 'display_order';
    public $sortDirection = 'asc';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;

    public ?int $editingSlideId = null;
    public ?int $slideToDelete = null;

    public string $title = '';
    public string $subtitle = '';
    public string $description = '';
    public string $button_text = '';
    public ?string $button_url = null;
    public ?int $display_order = null;
    public bool $is_active = true;
    public $image = null; // Livewire temporary uploaded file
    public ?string $existingImagePath = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $listeners = [
        'refreshLoginSlides' => '$refresh',
    ];

    public function mount(): void
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $slideId): void
    {
        $this->resetForm();
        $slide = LoginSlide::findOrFail($slideId);

        $this->editingSlideId = $slide->id;
        $this->title = $slide->title ?? '';
        $this->subtitle = $slide->subtitle ?? '';
        $this->description = $slide->description ?? '';
        $this->button_text = $slide->button_text ?? '';
        $this->button_url = $slide->button_url;
        $this->display_order = $slide->display_order;
        $this->is_active = (bool) $slide->is_active;
        $this->existingImagePath = $slide->image_path;

        $this->showFormModal = true;
    }

    public function confirmDelete(int $slideId): void
    {
        $this->slideToDelete = $slideId;
        $this->showDeleteModal = true;
    }

    public function saveSlide(): void
    {
        if ($this->button_url === '') {
            $this->button_url = null;
        }

        if ($this->display_order === '' || $this->display_order === null) {
            $this->display_order = null;
        }

        $validated = $this->validate($this->rules());
        $validated['button_url'] = $validated['button_url'] ?? null;
        $validated['display_order'] = $validated['display_order'] ?? $this->nextDisplayOrder();
        $validated['is_active'] = (bool) ($validated['is_active'] ?? $this->is_active);

        if ($this->editingSlideId) {
            $slide = LoginSlide::findOrFail($this->editingSlideId);
            if ($this->image) {
                $path = $this->image->store('login-slides', 'public');
                $this->deleteImageIfLocal($slide->image_path);
                $validated['image_path'] = $path;
            } else {
                $validated['image_path'] = $slide->image_path;
            }

            unset($validated['image']);

            $slide->update($validated);
            session()->flash('success', 'Slide berhasil diperbarui.');
        } else {
            $validated['image_path'] = $this->image->store('login-slides', 'public');
            unset($validated['image']);
            LoginSlide::create($validated);
            session()->flash('success', 'Slide baru berhasil ditambahkan.');
        }

        $this->closeFormModal();
        $this->resetForm();
        $this->dispatch('refreshLoginSlides');
    }

    public function deleteSlide(): void
    {
        if (!$this->slideToDelete) {
            return;
        }

        $slide = LoginSlide::find($this->slideToDelete);
        if ($slide) {
            $this->deleteImageIfLocal($slide->image_path);
            $slide->delete();
            session()->flash('success', 'Slide berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->slideToDelete = null;
        $this->dispatch('refreshLoginSlides');
    }

    public function toggleStatus(int $slideId): void
    {
        $slide = LoginSlide::findOrFail($slideId);
        $slide->update([
            'is_active' => !$slide->is_active,
        ]);
        $this->dispatch('refreshLoginSlides');
    }

    public function moveSlideUp(int $slideId): void
    {
        $slide = LoginSlide::findOrFail($slideId);
        $swap = LoginSlide::where('display_order', '<', $slide->display_order)
            ->orderBy('display_order', 'desc')
            ->first();

        if ($swap) {
            $this->swapOrder($slide, $swap);
        }
    }

    public function moveSlideDown(int $slideId): void
    {
        $slide = LoginSlide::findOrFail($slideId);
        $swap = LoginSlide::where('display_order', '>', $slide->display_order)
            ->orderBy('display_order')
            ->first();

        if ($swap) {
            $this->swapOrder($slide, $swap);
        }
    }

    private function swapOrder(LoginSlide $first, LoginSlide $second): void
    {
        $firstOrder = $first->display_order;
        $secondOrder = $second->display_order;

        $first->update(['display_order' => $secondOrder]);
        $second->update(['display_order' => $firstOrder]);

        $this->dispatch('refreshLoginSlides');
    }

    private function rules(): array
    {
        return [
            'title' => 'nullable|string|max:150',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:60',
            'button_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0|max:1000',
            'is_active' => 'required|boolean',
            // Enforce 4:5 ratio and at least 1080x1350 pixels
            'image' => [
                $this->editingSlideId ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
                'dimensions:min_width=1080,min_height=1350,ratio=4/5',
            ],
        ];
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingSlideId',
            'title',
            'subtitle',
            'description',
            'button_text',
            'button_url',
            'display_order',
            'is_active',
            'image',
            'existingImagePath',
        ]);

        $this->is_active = true;
    }

    private function closeFormModal(): void
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    private function deleteImageIfLocal(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function nextDisplayOrder(): int
    {
        $max = LoginSlide::max('display_order');
        return is_null($max) ? 0 : $max + 1;
    }

    public function render()
    {
        $slides = LoginSlide::query()
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('subtitle', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.login-slider-management', [
            'slides' => $slides,
        ]);
    }
}

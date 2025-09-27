<div class="space-y-4 max-w-md mx-auto h-full">
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="flex justify-center">
        <x-app.logo />
    </div>
    <section class="text-center">
        <h1 class="text-xl font-semibold">Sistem Manajemen Audit Pemerintah</h1>
        <h2 class="text-md">Ruang Urai Masalah Akuntabilitas Kompetensi dan
            Attitude dengan Tuntas</h2>
    </section>
    @if (session('message'))
        <div role="alert" class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('message') }}</span>
    </div>
    @endif
    @if (session('error'))
        <div role="alert" class="alert alert-warning">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    <form wire:submit="login" class="space-y-2">
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Email</legend>
            <input type="email" class="input w-full" placeholder="Type here" wire:model="email" />
        </fieldset>
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Kata Sandi</legend>
            <input type="password" class="input w-full" placeholder="Type here" wire:model="password" />
        </fieldset>
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Peran</legend>
            <select class="select w-full" wire:model="selectedRole">
                <option value="">Pilih Peran</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </fieldset>
        <div class="flex justify-between">
            <label class="label text-sm">
                <input type="checkbox" checked="checked" class="checkbox" />
                Remember me
            </label>
            <a href="#">
                Lupa Kata Sandi
            </a>
        </div>
        <button type="submit" class="btn btn-primary w-full">Masuk</button>
    </form>
</div>

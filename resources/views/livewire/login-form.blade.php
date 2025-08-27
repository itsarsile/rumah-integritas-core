<div class="space-y-4 max-w-md mx-auto">
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="flex justify-center">
    <livewire:app-logo />
    </div>
    <section class="text-center">
    <h1 class="text-xl font-semibold">Sistem Manajemen Audit Pemerintah</h1>
    <h2 class="text-md">Ruang Urai Masalah Akuntabilitas Kompetensi dan
        Attitude dengan Tuntas</h2>
    </section>
    <form class="space-y-2">
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Email</legend>
            <input type="email" class="input w-full" placeholder="Type here" wire:model="email" />
        </fieldset>
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Kata Sandi</legend>
            <input type="password" class="input w-full" placeholder="Type here" wire:model="password" />
        </fieldset>
        <div class="flex justify-between">
            <label class="label">
                <input type="checkbox" checked="checked" class="checkbox" />
                Remember me
            </label>
            <a href="#">
                Lupa Kata Sandi
            </a>
        </div>
        <button class="btn w-full">Masuk</button>
    </form>
</div>
<div class="container space-y-4 p-4">
    @foreach ($auditReports as $data)
        @if ($data->chatRoom)
            <a href="{{ route('dashboard.audit.chat', ['id' => $data->id]) }}" class="card relative border w-72 hover:bg-primary hover:text-primary-content transition-all duration-300 block">
                <div class="absolute top-2 right-2 badge badge-sm {{ $data->getStatusBadgeAttribute() }}">{{ $data->getStatusText() }}</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $data->report_title }}</h2>
                    <span>{{ $data->created_at }}</span>
                    <div class="flex justify-between">
                        <span class="flex flex-col">
                            <p>Pengirim</p>
                            <p>{{ $data->creator->name }}</p>
                        </span>
                        <span class="flex flex-col">
                            <p>Penerima</p>
                            <p>{{ $data->regionalGovernmentOrganization->name }}</p>
                        </span>
                    </div>
                </div>
            </a>
        @else
            <a href="{{ route('dashboard.audit.chat', ['id' => $data->id]) }}" class="card relative border w-72 hover:bg-primary hover:text-primary-content transition-all duration-300 block">
                <div class="absolute top-2 right-2 badge badge-sm {{ $data->getStatusBadgeAttribute() }}">{{ $data->getStatusText() }}</div>
                <div class="card-body">
                    <h2 class="card-title">{{ $data->report_title }}</h2>
                    <span>{{ $data->created_at }}</span>
                    <div class="flex justify-between">
                        <span class="flex flex-col">
                            <p>Pengirim</p>
                            <p>{{ $data->creator->name }}</p>
                        </span>
                        <span class="flex flex-col">
                            <p>Penerima</p>
                            <p>{{ $data->regionalGovernmentOrganization->name }}</p>
                        </span>
                    </div>
                </div>
            </a>
        @endif
    @endforeach
</div>
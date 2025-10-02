<div>
    @include('livewire.components.data-table', [
        'rows' => $this->rows,
        'title' => $this->title,
        'sortableColumns' => $this->sortableColumns,
        'filterableColumns' => $this->filterableColumns,
        'showFilters' => $this->showFilters,
        'searchableColumns' => $this->searchableColumns,
        'search' => $this->search,
        'filters' => $this->filters,
        'perPageOptions' => $this->perPageOptions,
        'perPage' => $this->perPage,
    ])

    <livewire:components.division-calendar-marks module="consumption" />
</div>


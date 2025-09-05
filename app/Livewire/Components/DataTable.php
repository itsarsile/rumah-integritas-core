<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class DataTable extends Component
{
    use WithPagination;

    // Public properties that can be set from parent component
    public $model;
    public $columns = [];
    public $searchableColumns = [];
    public $sortableColumns = [];
    public $filterableColumns = [];
    public $relationships = [];
    public $perPageOptions = [10, 25, 50, 100];
    public $defaultSort = 'id';
    public $defaultSortDirection = 'asc';
    public $tableClass = 'min-w-full divide-y divide-gray-200 z-0';
    public $headerClass = 'px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
    public $rowClass = 'bg-white divide-y divide-gray-200';
    public $cellClass = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';

    // Internal state properties
    public $search = '';
    public $sortBy = '';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $filters = [];
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => ''],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'filters' => ['except' => []],
        'page' => ['except' => 1]
    ];

    public function mount()
    {
        $this->sortBy = $this->defaultSort;
        $this->sortDirection = $this->defaultSortDirection;
        $this->perPage = $this->perPageOptions[0];
        
        // Initialize filters
        foreach ($this->filterableColumns as $column) {
            if (!isset($this->filters[$column['key']])) {
                $this->filters[$column['key']] = '';
            }
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if (!in_array($column, array_column($this->sortableColumns, 'key'))) {
            return;
        }

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filters = array_fill_keys(array_column($this->filterableColumns, 'key'), '');
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function getRowsProperty()
    {
        return $this->buildQuery()->paginate($this->perPage);
    }

    protected function buildQuery(): Builder
    {
        $query = $this->model::query();

        // Load relationships
        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        // Apply search
        if (!empty($this->search) && !empty($this->searchableColumns)) {
            $query->where(function (Builder $q) {
                foreach ($this->searchableColumns as $column) {
                    if (str_contains($column, '.')) {
                        // Handle relationship columns
                        $parts = explode('.', $column);
                        $relation = $parts[0];
                        $relationColumn = $parts[1];
                        
                        $q->orWhereHas($relation, function (Builder $relationQuery) use ($relationColumn) {
                            $relationQuery->where($relationColumn, 'like', '%' . $this->search . '%');
                        });
                    } else {
                        // Handle direct columns
                        $q->orWhere($column, 'like', '%' . $this->search . '%');
                    }
                }
            });
        }

        // Apply filters
        foreach ($this->filters as $column => $value) {
            if (!empty($value)) {
                $filterConfig = collect($this->filterableColumns)->firstWhere('key', $column);
                
                if ($filterConfig) {
                    switch ($filterConfig['type'] ?? 'text') {
                        case 'select':
                            $query->where($column, $value);
                            break;
                        case 'date':
                            $query->whereDate($column, $value);
                            break;
                        case 'date_range':
                            if (isset($value['from']) && !empty($value['from'])) {
                                $query->whereDate($column, '>=', $value['from']);
                            }
                            if (isset($value['to']) && !empty($value['to'])) {
                                $query->whereDate($column, '<=', $value['to']);
                            }
                            break;
                        case 'number_range':
                            if (isset($value['min']) && !empty($value['min'])) {
                                $query->where($column, '>=', $value['min']);
                            }
                            if (isset($value['max']) && !empty($value['max'])) {
                                $query->where($column, '<=', $value['max']);
                            }
                            break;
                        default:
                            $query->where($column, 'like', '%' . $value . '%');
                    }
                }
            }
        }

        // Apply sorting
        if (!empty($this->sortBy)) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query;
    }

    public function render()
    {
        return view('livewire.components.data-table', [
            'rows' => $this->rows,
            'sortableColumns' => $this->sortableColumns,
            'filterableColumns' => $this->filterableColumns,
            'showFilters' => $this->showFilters,
            'searchableColumns' => $this->searchableColumns,
            'search' => $this->search,
            'filters' => $this->filters,
            'perPageOptions' => $this->perPageOptions,
            'perPage' => $this->perPage
            
        ]);
    }
}
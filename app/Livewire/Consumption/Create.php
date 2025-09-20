<?php

namespace App\Livewire\Consumption;

use App\Models\ConsumptionReport;
use App\Models\ConsumptionType;
use App\Models\Division;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\ActivityLog;
use Livewire\WithFileUploads;
use Debugbar; // Add Debugbar facade

use Livewire\Attributes\Title;

#[Title('Buat Permintaan Konsumsi')]
class Create extends Component
{
    use WithFileUploads;

    public string $request_title = '';
    public string $event_request_date = '';
    public int $audience_count = 0;
    public string $email = '';
    public int $consumption_type_id = 0;
    public int $division_id = 0;
    public string $description = '';
    public $consumptionTypes;
    public $divisions;

    public function mount()
    {
        $this->consumptionTypes = ConsumptionType::all();
        $this->divisions = Division::all();
        Debugbar::info('Consumption Types loaded', $this->consumptionTypes->toArray());
    }

    public function submit()
    {
        Debugbar::info('Submit method called', [
            'user_id' => Auth::id(),
            'form_data' => $this->only([
                'request_title',
                'event_request_date',
                'audience_count',
                'email',
                'consumption_type_id',
                'description',
            ]),
        ]);

        try {
            if (!Auth::user()->hasPermissionTo('create consumption')) {
                Debugbar::error('Permission denied for user: ' . Auth::user()->id);
                session()->flash('error', 'Anda tidak memiliki izin untuk membuat permintaan konsumsi');
                return redirect()->back();
            }

            $this->validate([
                'request_title' => ['required', 'string', 'max:255'],
                'event_request_date' => ['required', 'date'],
                'audience_count' => ['required', 'integer', 'min:1'],
                'email' => ['required', 'email'],
                'consumption_type_id' => ['required', 'integer', 'exists:consumption_types,id'],
                'division_id' => ['required', 'integer', 'exists:divisions,id'],
                'description' => ['required', 'string'],
            ]);

            $requestData = [
                'request_code' => $this->generateRequestCode(),
                'request_title' => $this->request_title,
                'event_request_date' => $this->event_request_date,
                'audience_count' => $this->audience_count,
                'email' => $this->email,
                'consumption_type_id' => $this->consumption_type_id,
                'division_id' => $this->division_id,
                'description' => $this->description,
                'created_by' => Auth::id(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            Debugbar::info('Attempting to create ConsumptionReport', $requestData);
            $report = ConsumptionReport::create($requestData);
            // Log activity for submission
            ActivityLog::create([
                'user_id' => Auth::id(),
                'module' => 'consumption',
                'action' => 'submitted',
                'entity_type' => ConsumptionReport::class,
                'entity_id' => $report->id,
                'description' => 'Mengajukan permintaan konsumsi (' . $report->request_code . ')',
                'metadata' => [
                    'consumption_type_id' => $this->consumption_type_id,
                    'division_id' => $this->division_id,
                    'audience_count' => $this->audience_count,
                ],
            ]);
            Debugbar::info('ConsumptionReport created successfully', $report->toArray());
            session()->flash('message', 'Permintaan konsumsi berhasil dibuat');
            Debugbar::info('Redirecting to consumption.index');
            $this->resetForm();
        } catch (ValidationException $e) {
            Debugbar::error('Validation failed', $e->errors());
            throw $e;
        } catch (Exception $e) {
            Debugbar::error('Error creating ConsumptionReport', ['error' => $e->getMessage()]);
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function resetForm()
    {
        $this->request_title = '';
        $this->event_request_date = '';
        $this->audience_count = 0;
        $this->email = '';
        $this->consumption_type_id = 0;
        $this->division_id = 0;
        $this->description = '';
    }

    public function generateRequestCode(): string
    {
        return 'REQ-' . date('Ymd') . '-' . rand(1000, 9999);
    }

    public function render()
    {
        Debugbar::info('Rendering consumption.create view');
        return view('livewire.consumption.create');
    }
}

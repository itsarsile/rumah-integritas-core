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
    }

    public function submit()
    {
        try {
            // Use Gate-based check to avoid exceptions if permission is not yet created
            if (!Auth::user() || !Auth::user()->can('create consumption')) {
                session()->flash('error', 'Anda tidak memiliki izin untuk membuat permintaan konsumsi');
                return; // Stop here; let the page show the flash error
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
            session()->flash('message', 'Permintaan konsumsi berhasil dibuat');
            $this->resetForm();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            return; // Keep user on the form and show error
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
        return view('livewire.consumption.create');
    }
}

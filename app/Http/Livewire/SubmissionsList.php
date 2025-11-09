<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Submission;

class SubmissionsList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAge = '';
    public $filterWhatsapp = '';
    public $sortField = 'submitted_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'filterAge', 'filterWhatsapp'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $submissions = Submission::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('seat_qr_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterAge, function($query) {
                $query->where('age', $this->filterAge);
            })
            ->when($this->filterWhatsapp !== '', function($query) {
                $query->where('whatsapp_optin', $this->filterWhatsapp);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $stats = [
            'total' => Submission::count(),
            'whatsapp_yes' => Submission::where('whatsapp_optin', true)->count(),
            'unique_seats' => Submission::distinct('seat_qr_id')->count(),
        ];

        return view('livewire.submissions-list', compact('submissions', 'stats'));
    }
}

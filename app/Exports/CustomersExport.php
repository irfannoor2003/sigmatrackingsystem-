<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $query = Customer::with(['city', 'industry', 'category', 'salesman']);

        // Export only selected IDs
        if ($this->ids && count($this->ids) > 0) {
            $query->whereIn('id', $this->ids);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Contact Number  1',
            'Contact Number 2',
            'Email',
            'City',
            'Industry',
            'Category',
            'Salesman Name',     // ⭐ Added
            'Created At'
        ];
    }

    public function map($c): array
    {
        return [
            $c->id,
            $c->name,
            $c->phone1,
            $c->phone2,
            $c->email,
            $c->city->name ?? '-',
            $c->industry->name ?? '-',
            $c->category->name ?? '-',

            // ⭐ Salesman details
            $c->salesman->name ?? '-',


            $c->created_at->format('Y-m-d'),
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Customer;
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
        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Contact Number 1',
            'Contact Number 2',
            'Email',
            'City',
            'Industry',
            'Category',
            'Salesman Name',
            'Image',          // ⭐ Clickable Image Link
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
            $c->salesman->name ?? '-',

            // ✅ Clickable image link (opens in Chrome/browser)
            $c->image
                ? '=HYPERLINK("' . asset('storage/' . $c->image) . '", "View Image")'
                : '-',

            $c->created_at->format('Y-m-d'),
        ];
    }
}

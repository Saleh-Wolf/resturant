<?php

namespace App\Exports;

use App\Models\Bill;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        private ?string $fromDate = null,
        private ?string $toDate = null
    ) {}

    public function collection(): Collection
    {
        $query = Bill::with([
            'order.table',
            'order.waiter',
            'cashier',
        ])
            ->where('payment_status', 'paid');

        if ($this->fromDate) {
            $query->whereDate('paid_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->whereDate('paid_at', '<=', $this->toDate);
        }

        return $query
            ->latest()
            ->get()
            ->map(function ($bill) {
                return [
                    'bill_number' => $bill->bill_number,
                    'order_id' => $bill->order_id,
                    'table' => $bill->order->table->table_number ?? 'Takeaway',
                    'waiter' => $bill->order->waiter->name ?? '-',
                    'cashier' => $bill->cashier->name ?? '-',
                    'payment_method' => ucfirst($bill->payment_method),
                    'discount' => $bill->discount_total,
                    'total' => $bill->grand_total,
                    'paid_at' => $bill->paid_at,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Bill Number',
            'Order ID',
            'Table',
            'Waiter',
            'Cashier',
            'Payment Method',
            'Discount',
            'Total',
            'Paid At',
        ];
    }
}
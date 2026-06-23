<?php

namespace App\Exports;

use App\Models\Offer;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OffersPerformanceExport implements FromCollection, WithHeadings
{
    public function __construct(
        private ?string $fromDate = null,
        private ?string $toDate = null,
        private ?string $sort = null
    ) {}

    public function collection(): Collection
    {
        $offers = Offer::withCount('menuItems')->get();

        $offerStats = $offers->map(function ($offer) {
            $query = OrderItem::where('offer_id', $offer->id);

            if ($this->fromDate) {
                $query->whereHas('order.bill', function ($q) {
                    $q->whereDate('paid_at', '>=', $this->fromDate);
                });
            }

            if ($this->toDate) {
                $query->whereHas('order.bill', function ($q) {
                    $q->whereDate('paid_at', '<=', $this->toDate);
                });
            }

            $timesUsed = $query->count();
            $totalDiscount = $query->sum(DB::raw('discount_amount * quantity'));
            $revenue = $query->sum('total_price');

            return [
                'offer_name' => $offer->name,
                'active_period' => ($offer->start_date ?? '-') . ' - ' . ($offer->end_date ?? '-'),
                'items_count' => $offer->menu_items_count,
                'times_used' => $timesUsed,
                'total_discount' => $totalDiscount,
                'revenue' => $revenue,
                'status' => $offer->is_active ? 'Active' : 'Inactive',
            ];
        });

        if ($this->sort === 'usage') {
            $offerStats = $offerStats->sortByDesc('times_used');
        } elseif ($this->sort === 'discount') {
            $offerStats = $offerStats->sortByDesc('total_discount');
        } elseif ($this->sort === 'revenue') {
            $offerStats = $offerStats->sortByDesc('revenue');
        }

        return $offerStats->values();
    }

    public function headings(): array
    {
        return [
            'Offer Name',
            'Active Period',
            'Items Count',
            'Times Used',
            'Total Discount',
            'Revenue',
            'Status',
        ];
    }
}
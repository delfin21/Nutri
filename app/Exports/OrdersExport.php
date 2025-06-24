<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with(['buyer', 'product.farmer'])->get()->map(function ($order) {
            return [
                'Order Code'     => $order->order_code,
                'Product Name'   => $order->product->name ?? 'N/A',
                'Farmer Name'    => $order->product->farmer->name ?? 'N/A',
                'Buyer Name'     => $order->buyer->name ?? 'N/A',
                'Quantity'       => $order->quantity,
                'Total Price'    => $order->total_price,
                'Status'         => $order->status,
                'Created At'     => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Order Code',
            'Product Name',
            'Farmer Name',
            'Buyer Name',
            'Quantity',
            'Total Price',
            'Status',
            'Created At',
        ];
    }
}

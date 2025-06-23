<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $orders = Order::with(['buyer', 'product.farmer'])->latest()->get();

        return $orders->map(function ($order) {
            return [
                'Order Code' => $order->order_code,
                'Product Name' => $order->product->name ?? 'N/A',
                'Farmer Name' => $order->product->farmer->name ?? 'N/A',
                'Buyer Name' => $order->buyer->name ?? 'N/A',
                'Buyer Email' => $order->buyer->email ?? '—',
                'Delivery Address' => implode(', ', array_filter([
                    $order->buyer_address,
                    $order->buyer_city,
                    $order->buyer_region,
                    $order->buyer_postal_code
                ])),
                'Quantity' => $order->quantity,
                'Total Price' => $order->total_price,
                'Status' => $order->status,
                'Created At' => $order->created_at->format('d M Y, h:i A'),
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'Order Code',
            'Product Name',
            'Farmer Name',
            'Buyer Name',
            'Buyer Email',
            'Delivery Address',
            'Quantity',
            'Total Price',
            'Status',
            'Created At',
        ];
    }
}

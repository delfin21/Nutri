<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromArray, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        return $this->orders->map(function ($order) {
            return [
                'Order Code'        => $order->order_code,
                'Product Name'      => $order->product->name ?? 'N/A',
                'Category'          => $order->product->category ?? 'N/A',
                'Farmer Name'       => $order->product->farmer->name ?? 'N/A',
                'Buyer Name'        => $order->buyer->name ?? 'N/A',
                'Buyer Email'       => $order->buyer->email ?? '—',
                'Delivery Address'  => implode(', ', array_filter([
                    $order->buyer_address,
                    $order->buyer_city,
                    $order->buyer_region,
                    $order->buyer_postal_code
                ])),
                'Quantity'          => $order->quantity,
                'Total Price (₱)'   => number_format($order->total_price, 2),
                'Status'            => ucfirst($order->status),
                'Order Date'        => $order->created_at->format('Y-m-d h:i A'),
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'Order Code',
            'Product Name',
            'Category',
            'Farmer Name',
            'Buyer Name',
            'Buyer Email',
            'Delivery Address',
            'Quantity',
            'Total Price (₱)',
            'Status',
            'Order Date',
        ];
    }
}

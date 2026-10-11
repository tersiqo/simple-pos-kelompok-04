<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('items') && is_array($this->items)) {
                foreach ($this->items as $item) {
                    if (!empty($item['product_id']) && !empty($item['qty'])) {
                        $product = Product::find($item['product_id']);
                        if ($product && $item['qty'] > $product->stock) {
                            $validator->errors()->add(
                                'items',
                                "Stok untuk {$product->name} tidak mencukupi (tersedia: {$product->stock}, diminta: {$item['qty']})."
                            );
                        }
                    }
                }
            }
        });
    }
}
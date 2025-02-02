<?php

namespace Modules\Invoice\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Invoice\Domain\ValueObject\ProductName;

class AddInvoiceProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => sprintf('required|max:%d', ProductName::MAX_LENGTH),
            'quantity' => sprintf('required|min:%d', 1),
            'price' => sprintf('required|min:%d', 1),
        ];
    }
}

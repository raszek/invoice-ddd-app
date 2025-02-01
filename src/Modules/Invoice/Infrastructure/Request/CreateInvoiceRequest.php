<?php

namespace Modules\Invoice\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;

class CreateInvoiceRequest extends FormRequest
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
            'customerName' => sprintf('required|max:%d', CustomerName::MAX_LENGTH),
            'customerEmail' => sprintf('required|max:%d|email', CustomerEmail::MAX_LENGTH),
        ];
    }
}

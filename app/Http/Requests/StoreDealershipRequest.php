<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreDealershipRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        if ($this->has('store')) {
            $stores = collect($this->stores)->map(function ($store) {
                if (isset($store['phone'])) {
                    $store['phone'] = preg_replace('/\D/', '', $store['phone']);
                }

                return $store;
            })->toArray();

            $this->merge(['stores' => $stores]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'stores' => ['required', 'array', 'min:1'],
            'stores.*.name' => ['required', 'string', 'max:255'],
            'stores.*.address' => ['required', 'string', 'max:255'],
            'stores.*.city' => ['required', 'string', 'max:255'],
            'stores.*.state' => ['required', 'string', Rule::in(array_column(State::cases(), 'value'))],
            'stores.*.zip' => ['required', 'string', 'max:255'],
            'stores.*.phone' => ['nullable', 'string', 'max:255'],
            'stores.*.timezone' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The dealership name is required.',
            'stores.required' => 'At least one store is required.',
            'stores.*.name.required' => 'The store name is required.',
            'stores.*.address.required' => 'The store address is required.',
            'stores.*.city.required' => 'The store city is required.',
            'stores.*.state.required' => 'The store state is required.',
            'stores.*.zip.required' => 'The store zip code is required.',
            'stores.*.timezone.required' => 'The store timezone is required.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

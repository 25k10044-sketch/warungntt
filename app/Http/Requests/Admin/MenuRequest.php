<?php

namespace App\Http\Requests\Admin;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $menu = $this->route('menu');

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('menus', 'slug')->ignore($menu instanceof Menu ? $menu->id : null)],
            'description' => ['required', 'string', 'max:5000'],
            'story' => ['required', 'string', 'max:5000'],
            'origin_region' => ['required', 'string', 'max:255'],
            'main_ingredients' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }
}

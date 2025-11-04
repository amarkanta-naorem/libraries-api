<?php

namespace App\Http\Requests\Book;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Book;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @property-read Book $book
 * @method bool hasFile(string $key)
 * @method \Illuminate\Http\UploadedFile|null file(string $key = null, $default = null)
 */
class BookRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse(
            'Validation error. Please check your input.',
             $validator->errors(),
             422
        ));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:books,id'],
            'isbn' => [
                'required',
                'string',
                'regex:/^\d{10}(\d{3})?$/',
                'max:13',
                Rule::unique('books')->ignore($this->book)
            ],
            'name' => ['required', 'string', 'max:255', Rule::unique('books')->ignore($this->book)],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,png', 'max:2048'],
            'edition' => ['nullable', 'string', 'max:100'],
            'format' => ['required', Rule::in(['hardcover', 'paperback', 'e-book', 'audiobook'])],
            'language' => ['required', 'string', 'max:10']
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'The selected book does not exist in the database.',
            'isbn.required' => 'Please enter an ISBN number.',
            'isbn.string' => 'ISBN must be a valid string of digits.',
            'isbn.regex' => 'ISBN must be 10 or 13 digits long and contain only numbers.',
            'isbn.max' => 'ISBN must not exceed 13 digits.',
            'isbn.unique' => 'This ISBN has already been registered.',

            'name.required' => 'The book name is required.',
            'name.string' => 'The book name must be a string.',
            'name.max' => 'The book name must not exceed 255 characters.',
            'name.unique' => 'This book name is already in use.',

            'thumbnail.file' => 'The thumbnail must be a valid file.',
            'thumbnail.max' => 'The thumbnail file size cannot exceed 2MB.',
            'thumbnail.mimes' => 'The thumbnail must be a JPG or PNG image.',

            'edition.string' => 'Edition must be a valid text value.',
            'edition.max' => 'Edition must not exceed 100 characters.',

            'format.required' => 'Please select a format for the book.',
            'format.in' => 'Format must be one of: hardcover, paperback, e-book, or audiobook.',

            'language.required' => 'Please specify the language of the book.',
            'language.string' => 'Language must be a text value.',
            'language.max' => 'Language code cannot exceed 10 characters.',
        ];
    }

    public function attributes(): array
    {
        return [
            'parent_id' => 'parent book',
            'isbn' => 'ISBN number',
            'name' => 'book title',
            'thumbnail' => 'book cover image',
            'edition' => 'book edition',
            'format' => 'book format',
            'language' => 'book language',
        ];
    }
}

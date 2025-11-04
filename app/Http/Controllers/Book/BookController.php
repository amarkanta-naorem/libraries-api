<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use App\Services\Media\ImageService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use ApiResponse;

    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(): BookCollection
    {
        return new BookCollection(Book::all());
    }

    public function store(BookRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $validated['slug'] = Str::slug($validated['name']);

            $thumbnailPath = null;

            if ($request->hasFile('thumbnail')) {
                $uploadedResult = $this->imageService->upload($request->file('thumbnail'), 'books/thumbnails');

                $thumbnailPath = $uploadedResult['url'];
            }

            $validated['thumbnail'] = $thumbnailPath;
            $data = Book::create($validated);
            DB::commit();

            return $this->successResponse(new BookResource($data), 'Book created successfully.', 201);
        } catch (ValidationException $exception) {
            DB::rollBack();

            return $this->errorResponse('Validation failed.', $exception->errors(), 422);
        } catch (QueryException $exception) {
            DB::rollBack();
            report($exception);

            return $this->errorResponse('Database error occurred.', ['sql' => $exception->getMessage()], 500);
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return $this->errorResponse('Failed to create the book.', ['exception' => $exception->getMessage()], 500);
        }
    }
}

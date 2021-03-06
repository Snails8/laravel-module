<?php

namespace App\Http\Controllers\_Templates\ApiCrud;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogPostRequest;
use App\Models\Blog;
use App\Services\Utility\ApiErrorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateController extends Controller
{
    private $apiErrorService;

    public function __construct(ApiErrorService $apiErrorService)
    {
        $this->apiErrorService = $apiErrorService;
    }

    /**
     * @param BlogPostRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(BlogPostRequest $request, int $id): JsonResponse
    {
        $blog = Blog::query()->find($id);

        $blog?->fill($request->validated())->save();

        return $blog
            ? response()->json($blog, 200)->withHeaders([
                'Content-Type'     => 'application/json',
                'Content-Language' => 'en',
                'Location'         => 'https://localhost/v2.0/blogs',
            ])
            : response()->json($this->apiErrorService->getNotFoundError($id), 404)->withHeaders([
                'Content-Type'     => 'application/problem+json',
                'Content-Language' => 'en',
                'Location'         => 'invalid',
            ]);
    }
}
// ------------------------------------------------------------
// json を返却するので 200
// ------------------------------------------------------------
//
//  {
//    "id": 1,
//    "title": "test",
//    "detail": "updated",
//    "created_at": null,
//    "updated_at": "2022-01-28T00:44:49.000000Z"
//  }


//  403 Forbiddon
//  HTTP 403、またはエラーメッセージ Forbiddenは、HTTPステータスコードの一つ。ページが存在するものの、特定のアクセス者にページを表示する権限が付与されず、アクセスが拒否されたことを示すもの
//  validation の $rules で許可していないと発生する
// ------------------------------------------------------------
// 存在しないid
// ------------------------------------------------------------
//  {
//    "error": {
//        "message": "record not found: id=15"
//    }
//  }
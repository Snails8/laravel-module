<?php

namespace App\Http\Controllers\Api\Test\Rest;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * resr api の簡易版 json の挙動検証用
 */
class IndexController extends Controller
{
    /**
     * URI  http://localhost/api/users?fields=title,sample
     *      http://localhost/api/users?fields               -> fields => null
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // User が任意で値を取得できるような設計
        $params = $request->query()['fields'] ?? '';  //  'fields' => 'title,sample',"

        // クエリに応じて単体で取得できる処理
        if ($params) {
            $columns = explode(',', $params);   // カラムが存在すれば返却, TODO::クエリで指定したカラムが 無いと500履く

            $data = Blog::query()->select($columns)->get();
        } else {
            $data = $this->getBlogs();
        }

//        $data = 1000;
//        $data = [];
//        $data = ['sample' => 'hoge'];
//        $data = $this->getArrayBlogs();

        return $data ? response()->json($data, 200)->withHeaders([
            'Content-Type'     => 'application/json',
            'Content-Language' => 'en'])
            : response()->json($data, 204)->withHeaders([
                'Content-Type'     => 'application/problem+json',
                'Content-Language' => 'en',
            ]);
    }

    /**
     * @return array
     */
    private function getArrayBlogs(): array
    {
        $text = 'sample text';
        $blogs = Blog::query()->get();

        $data = [
            'blogs' => $blogs,
            'text' => $text,
        ];

        return $data;
    }
}
// | 200 | OK           |
// | 204 | Not Content  | null , ''. []
// | 500 |  Internal Server Error
//  TODO::304 Not Modified：条件付きGETでキャッシュを使わせたい場合

// ---------------------------------------------
//    配列で送る
//    $data = Blogs::query->get();
// ---------------------------------------------
//  直接配列が変える
//  [
//    {
//        "id": 1,
//        "title": "sample",
//        "detail": "sample detail",
//        "created_at": null,
//        "updated_at": null
//    },
//    {
//        "id": 2,
//        "title": "test",
//        "detail": "test detail",
//        "created_at": null,
//        "updated_at": null
//    },
//   ...// 直接配列で変える
//  ]
// ---------------------------------------------

// ---------------------------------------------
//  よくあるパターン  配列を含む 連想配列
//  $data = [’blogs’ ⇒ $blogs,  ‘title’ ⇒ $title];
// ---------------------------------------------
//  {
//    "blogs": [
//        {
//            "id": 1,
//            "title": "sample",
//            "detail": "sample detail",
//            "created_at": null,
//            "updated_at": null
//        },
//        {
//            "id": 2,
//            "title": "test",
//            "detail": "test detail",
//            "created_at": null,
//            "updated_at": null
//        },
//    ....//
//    ],
//    "title": "sample text"
//  }
// ---------------------------------------------

// ---------------------------------------------
//  文字列で送る
//  $data = "sample"
// ---------------------------------------------
//  "sample"        そのまま文字列に
// ---------------------------------------------


// ---------------------------------------------
//   配列に格納する
//   $data = ['sample'];
// ---------------------------------------------
//   [
//    "sample"
//   ]
// ---------------------------------------------

// ---------------------------------------------
//   連想配列に格納する
//   $data = ['sample' => 'hoge'];
// ---------------------------------------------
//  {
//    "sample": "hoge"
//  }
// ---------------------------------------------
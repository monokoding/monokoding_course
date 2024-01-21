<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryCourseController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return new CourseResource(true, 'List Data Course', $categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categories = Category::create([
            'title'     => $request->title,
        ]);

        return new CourseResource(true, 'Data Category Berhasil Ditambahkan!', $categories);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categories = Category::find($id);

        $categories->update([
            'title'     => $request->title
        ]);
        return new CourseResource(true, 'Data Category Berhasil Diubah!', $categories);
    }

    public function destroy($id)
    {
        $categories = Category::find($id);
        $categories->delete();
        return new CourseResource(true, 'Data Category Berhasil Dihapus!', null);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $course = Course::latest()->paginate(10);
        return new CourseResource(true, 'List Data Course', $course);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/course', $image->hashName());

        $course = Course::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        return new CourseResource(true, 'Data Course Berhasil Ditambahkan!', $course);
    }

    public function show($id)
    {
        $course = Course::find($id);
        return new CourseResource(true, 'Detail Data Course!', $course);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $course = Course::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/course', $image->hashName());

            Storage::delete('public/course/' . basename($course->image));

            $course->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        } else {
            $course->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }
        return new CourseResource(true, 'Data Course Berhasil Diubah!', $course);
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        Storage::delete('public/course/' . basename($course->image));
        $course->delete();
        return new CourseResource(true, 'Data Course Berhasil Dihapus!', null);
    }
}

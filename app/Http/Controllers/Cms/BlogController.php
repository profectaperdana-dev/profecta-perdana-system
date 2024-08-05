<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\BlogCategoryModel;
use App\Models\Cms\BlogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $get_blog = BlogModel::with(['categoryBy', 'authorBy'])->latest()->get();
        $get_category = BlogCategoryModel::oldest('name')->get();

        $data = [
            'title' => 'Blog Content Management',
            'blogs' => $get_blog,
            'categories' => $get_category
        ];
        // dd(date('Y-m-d H:i:s'));

        return view('cms.blogs.index', $data);
    }

    public function read($slug)
    {
        $get_blog = BlogModel::where('slug', $slug)->first();

        $data = [
            'title' => $get_blog->title,
            'content' => $get_blog
        ];
        return view('cms.blogs.read', $data);
    }

    public function write()
    {
        $get_category = BlogCategoryModel::oldest('name')->get();

        $data = [
            'title' => 'Writing Page',
            'categories' => $get_category
        ];
        return view('cms.blogs.write', $data);
    }

    public function edit($slug)
    {
        $get_blog = BlogModel::where('slug', $slug)->first();
        $get_category = BlogCategoryModel::oldest('name')->get();

        $data = [
            'title' => 'Edit Writing Page',
            'content' => $get_blog,
            'categories' => $get_category
        ];
        return view('cms.blogs.edit', $data);
    }

    public function delete($id)
    {
        $blog = BlogModel::where('id', $id)->first();
        $path = public_path('images/cms/blogs/') . $blog->img_header;
        if (File::exists($path)) {
            File::delete($path);
        }
        $blog->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting Blog success!'
        ]);
    }

    public function save_as_draft(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'img' => 'image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }

        $id = $request->id;
        $blog = BlogModel::where('id', $id)->first();

        if (!$blog) {
            $blog = new BlogModel();
        }

        $slug = strtolower($request->title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->author = Auth::user()->id;
        $blog->article = $request->content;

        $textOnly = strip_tags($request->content_publish);
        $textOnly = html_entity_decode($textOnly);
        $textOnlyShortened = substr($textOnly, 0, 350) . '...';
        // dd($cleanedText);
        $blog->preview = $textOnlyShortened;

        $blog->category_id = $request->category_id;
        $blog->isposted = false;

        $img = $request->img;
        if ($img) {
            $path = public_path('images/cms/blogs/') . $blog->img_header;
            if (File::exists($path)) {
                File::delete($path);
            }
            $directory = 'images/cms/blogs';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $name_img = time() . '.' . $img->extension();
            $img->move(public_path($directory), $name_img);
            $blog->img_header = $name_img;
        }
        $blog->save();

        return response()->json([
            'status' => 200,
            'message' => 'Save as Draft success!',
            'data' => $blog
        ]);
    }

    public function publish(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'img' => 'image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }

        $id = $request->id;
        $blog = BlogModel::where('id', $id)->first();

        if (!$blog) {
            $blog = new BlogModel();
        }

        $slug = strtolower($request->title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->author = Auth::user()->id;
        $blog->article = $request->content_publish;

        $textOnly = strip_tags($request->content_publish);
        $textOnly = html_entity_decode($textOnly);
        $textOnlyShortened = substr($textOnly, 0, 350) . '...';
        // dd($cleanedText);
        $blog->preview = $textOnlyShortened;

        $blog->category_id = $request->category_id;
        $blog->isposted =  1;
        $blog->post_date = date('Y-m-d H:i:s');

        $img = $request->img;
        if ($img) {
            $path = public_path('images/cms/blogs/') . $blog->img_header;
            if (File::exists($path)) {
                File::delete($path);
            }
            $directory = 'images/cms/blogs';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $name_img = time() . '.' . $img->extension();
            $img->move(public_path($directory), $name_img);
            $blog->img_header = $name_img;
        }
        $blog->save();

        return redirect('cms/blog')->with('success', 'Content has been published!');
    }

    public function store_category(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }

        $category = new BlogCategoryModel();
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Adding blog category success!',
            'data' => $category
        ]);
    }

    public function edit_category(Request $request, $id)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }
        $category = BlogCategoryModel::where('id', $id)->first();
        $category->name = $request->name;

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Editing blog category success!',
            'data' => $category
        ]);
    }

    public function delete_category($id)
    {
        $category = BlogCategoryModel::where('id', $id)->first();
        $category->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting Blog Category success!'
        ]);
    }

    public function api_getblog()
    {
        $blog = BlogModel::with(['categoryBy', 'authorBy'])->where('isposted', 1)->latest()->paginate(5);
        return response()->json([
            'status' => 200,
            'data' => $blog
        ]);
    }

    public function api_recent()
    {
        $blog = BlogModel::with(['authorBy'])
            ->where('isposted', 1)->select('img_header', 'title', 'post_date', 'slug', 'preview', 'author')
            ->latest()->take(3)->get();
        return response()->json([
            'status' => 200,
            'data' => $blog
        ]);
    }

    public function api_categories()
    {
        $blog = BlogCategoryModel::withCount('blogBy')->oldest('name')->get();
        return response()->json([
            'status' => 200,
            'data' => $blog
        ]);
    }

    public function api_read($slug)
    {
        $blog = BlogModel::with(['categoryBy', 'authorBy'])->where('slug', $slug)->first();
        return response()->json([
            'status' => 200,
            'data' => $blog
        ]);
    }

    public function api_filterbycategory($category_id)
    {
        $blog = BlogModel::with(['categoryBy', 'authorBy'])->where('isposted', 1)
            ->whereHas('categoryBy', function ($q) use ($category_id) {
                $q->where('name', $category_id);
            })->latest()->paginate(5);
        return response()->json([
            'status' => 200,
            'data' => $blog
        ]);
    }

    public function api_search($text)
    {
        $blog = BlogModel::with(['categoryBy', 'authorBy'])->where('isposted', 1)
            ->where('title', 'LIKE', "%$text%")->latest()->paginate(5);
        return response()->json([
            'status' => 200,
            'data' => $blog
        ]);
    }
}

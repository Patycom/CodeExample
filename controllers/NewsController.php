<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Requests\NewsRequest;
use App\Repositories\NewsRepository;
use App\User;
use Illuminate\Http\Request;
use App\News;
use Illuminate\Support\Facades\Gate;

class NewsController extends AdminController
{
    public function __construct(NewsRepository $n_rep)
    {
        parent::__construct();

        $this->template = env('THEME').'.Admin.news';
        $this->n_rep = $n_rep;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->title = 'News editor';

        $news = $this->getNews();

        $this->content =view(env('THEME').'.admin.content.news_content')->with(['news'=> $news,'title'=>$this->title])->render();

        return $this->renderOutput();
    }


    public function getNews(){

        return $this->n_rep->get('*',false,true,'',10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->title = "Create news";

        $news = new News();

        $categories = Category::select('title', 'alias', 'id')->get();

        $lists[] = 'Choose category';

        foreach ($categories as $category){

            $lists[] = $category->title;
        }

        $this->content = view(env('THEME').'.admin.content.news_create_content')->with(['categories'=>$lists, 'news'=>$news, 'title'=>$this->title])->render();

        return $this->renderOutput();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {

        $result = $this->n_rep->addNews($request);

//        if (is_array($result) && !empty($result['error'])){
//
//            return back()->with($result);
//
//        }

        return redirect('/admin/news')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(News $post)
    {


        $this->title = "Edit post - "."'".$post->name."'";

        $categories = Category::select('title', 'alias', 'id')->get();

        $lists[] = 'Choose category';

        foreach ($categories as $category){

            $lists[] = $category->title;
        }

        $this->content = view(env('THEME').'.admin.content.news_create_content')->with(['categories'=>$lists, 'news'=>$post, 'title'=>$this->title])->render();


        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $request, News $post)
    {
        $result = $this->n_rep->updateNews($request, $post);

        return redirect('/admin/news')->with($result);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( News $post)
    {


        $result = $this->n_rep->deleteNews($post);

        return redirect('/admin/news')->with($result);

    }
}

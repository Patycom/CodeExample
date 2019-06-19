<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Menu as ModelMenu;
use App\Repositories\MenusRepository;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
use Response;
use App\Http\Requests\MenusRequest;
use Menu;

class MenuController extends AdminController
{

    public function __construct(MenusRepository $m_rep, NewsRepository $n_rep)
    {
        parent::__construct();

        $this->template = env('THEME').'.Admin.menu';
        $this->m_rep = $m_rep;
        $this->n_rep = $n_rep;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = 'Menus editor';

        $menu = $this->getSiteMenu();

        $this->content = view(env('THEME').'.admin.content.menu_content')->with(['menu'=>$menu->roots(), 'title'=>$this->title, 'padding'=>''])->render();

        return $this->renderOutput();
    }

    /**
     * @return bool
     */

    public function getSiteMenu ()
    {
        $menu = $this->m_rep->get()->sortBy('position');

        if (empty($menu)) return false;

        $mBuilder = Menu::make('mBuilder', function ($m) use ($menu) {

            foreach ($menu as $item){

                if ($item->parent_id == 0){
                    $m->add($item->title, $item->path)->id($item->id);

                } else {
//
                    if ($m->find($item->parent_id) ){

                        $m->find($item->parent_id)->add($item->title,$item->path)->id($item->id) ;
                    }
                }
            }

        });

        return $mBuilder;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function onchangeSelect(Request $request){

        $category = $request->input('category');

        $materialsSelect = $this->m_rep->materialsSelect($category);

        $materialsSelect = $materialsSelect->reduce(function ($returnNews, $materialsSelect){

            $returnNews[$materialsSelect->alias] = $materialsSelect->name;
            return $returnNews;

        }, ['0'=>'Choose material']);


        return Response::json($materialsSelect);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveMenuPosition(Request $request){

        $position = $request->input('menu');

        foreach ($position as $k => $v){

            $result = $this->m_rep->savePosition($k,$v);

            if ($result === false){

                return Response::json($result);
            }
        }

        return Response::json(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = 'Create menu item';

        $tmp = $this->getSiteMenu()->roots();

        $menus = $tmp->reduce(function ($returnMenus, $menu){

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;

        },['0'=>'Parent menu']);

        $categories = Category::all(['id', 'title', 'alias', 'parent_id']);

        $categoryList = [];

        $categoryList['parent'] = 'Blog';

        foreach ($categories as $cat){

            if ($cat->parent_id == 0) {

                if ($categories->where('parent_id', '=', $cat->id)->first()) {

                    $categoryList[$cat->title] = [$cat->alias => $cat->title];

                } else {
                    $categoryList[$cat->alias] = $cat->title;
                }

            } else {

                $categoryList[$categories->where('id','=',$cat->parent_id)->first()->title][$cat->alias] = $cat->title;
            }
        }
        $news = [];

        $this->content = view(env('THEME').'.admin.content.menu_create_content')->with(['menu'=>$menus, 'categoryList'=>$categoryList, 'news' => $news]);

        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenusRequest $request)
    {
        $result = $this->m_rep->addMenu($request);
        if ($result) {
            return redirect('/admin/menu')->with('status','Item is added');
        }

        return redirect('/admin/menu')->with('errors', $result);
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
    public function edit(ModelMenu $menu)
    {
//        dd($menu->parent_id);

        $this->title = "Edit menu \"$menu->title\"";

        $categories = Category::all(['id', 'title', 'alias', 'parent_id']);

        $categoryList = [];

        $categoryList['parent'] = 'Blog';

        foreach ($categories as $cat){

            if ($cat->parent_id == 0) {

                if ($categories->where('parent_id', '=', $cat->id)->first()) {

                    $categoryList[$cat->title] = [$cat->alias => $cat->title];

                } else {
                    $categoryList[$cat->alias] = $cat->title;
                }

            } else {

                $categoryList[$categories->where('id','=',$cat->parent_id)->first()->title][$cat->alias] = $cat->title;
            }
        }
        $news = [];

        $tmp = $this->getSiteMenu()->roots();

        $menus = $tmp->reduce(function ($returnMenus, $menu){

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;

        },['0'=>'Parent menu']);

        $this->content = view(env('THEME').'.admin.content.menu_create_content')->with(['menu'=>$menu, 'categoryList'=>$categoryList, 'menus' => $menus]);

        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModelMenu $menu)
    {
        $result = $this->m_rep->updateMenu($request, $menu);
        if ($result) {
            return redirect('/admin/menu')->with('status','Item is updated');
        }

        return redirect('/admin/menu')->with('errors', $result);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelMenu $menu)
    {
        $result = $this->m_rep->deleteMenu($menu);
        if ($result) {
            return redirect('/admin/menu')->with('status','Item is deleted');
        }

        return redirect('/admin/menu')->with('errors', $result);
    }
}

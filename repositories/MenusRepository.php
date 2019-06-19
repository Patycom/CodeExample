<?php

namespace App\Repositories;

use App\Menu;
use App\Category;
use App\News;

class MenusRepository extends Repository
{
    /**
     * MenusRepository constructor.
     * @param Menu $menu
     */
    public function __construct(Menu $menu) {

        $this->model = $menu;
    }

    public function savePosition($position, $id){

        $item = Menu::find($id);

        if ($item->parent_id != 0) {

            $parent = Menu::find($item->parent_id);

            if ($parent->position >= $position){
                return false;
            }
        }

        $item->position = $position;

        $item->save();

        return true;

    }

    public function materialsSelect($category){

        $cat_id = Category::all()->where('alias',$category)->first();
        $material = News::all()->where('cat_id', '=', $cat_id->id);

        return $material;

    }

    public function addMenu($request){

        $data = $request->only('type', 'title', 'parent_id', 'description');

        if (empty($data)){

            return back()->with(['errors' => 'No data']);
        }

        switch ($data['type']){

            case 1:
                $data['path'] = $request->input('custom_link');
                break;
            case 2:
                $category = $request->input('category');
                if (isset($category)){

                    if ($category == 'parent'){
                        $data['path'] = route('news.index');

                    } else {
                        $material_alias = $request->input('material_alias');

                        if (isset($material_alias) && $material_alias != 0) {

                            $data['path'] = route('news.show', ['alias' => $material_alias]);

                        } elseif (isset($material_alias) && $material_alias == 0) {

                            $data['path'] = route('newsCat', ['cat_alias' => $category]);

                        }
                    }
                }

                break;
        }

        $position = Menu::all()->count();

        $data['position'] = $position;

        unset($data['type']);

        if ($this->model->fill($data)->save()){

            return true;
        } else {

            return ['status' => 'Error'];
        }
    }

    public function updateMenu($request, $menu) {

        $data = $request->only('type', 'title', 'parent_id', 'description');

        if (empty($data)){

            return back()->with(['errors' => 'No data']);
        }

        switch ($data['type']){

            case 1:
                $data['path'] = $request->input('custom_link');
                break;
            case 2:
                $category = $request->input('category');
                if (isset($category)){

                    if ($category == 'parent'){
                        $data['path'] = route('news.index');

                    } else {
                        $material_alias = $request->input('material_alias');

                        if (isset($material_alias) && $material_alias != 0) {

                            $data['path'] = route('news.show', ['alias' => $material_alias]);

                        } elseif (isset($material_alias) && $material_alias == 0) {

                            $data['path'] = route('newsCat', ['cat_alias' => $category]);

                        }
                    }
                }

                break;
        }

//        $position = Menu::all()->count();
//
//        $data['position'] = $position;

        unset($data['type']);

        if ($menu->fill($data)->update($data)){

            return true;
        } else {

            return ['status' => 'Error'];
        }
    }

    public function deleteMenu($menu){

        if ($menu->delete()) return true;
        else return ['errors' => 'Error'];
    }
}
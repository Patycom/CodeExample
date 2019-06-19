<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Menu;

class AdminController extends \App\Http\Controllers\Controller
{
    /**
     * @var object $p_rep page obj
     * @var object $n_rep news obj
     * @var object $m_rep menu obj
     * @var object $perm_rep permission obj
     * @var object $role_rep roles obj
     * @var object $sidebar sidebar obj
     * @var object $user user obj
     * @var string $content content return render
     * @var array $vars
     *
     */

    protected $p_rep;

    protected $n_rep;

    protected $m_rep;

    protected $perm_rep;

    protected $role_rep;

    protected $sidebar;

    protected $user;

    protected $template;

    protected $content = false;

    protected $title;

    protected $vars = [];

    public function __construct()
    {
    }

    public function renderOutput(){

        $this->vars['title'] = $this->title;

        $menu = $this->getMenu();
        $admin = Auth::user();

        $navigation = view(env('THEME').'.admin.navigation')->with(['menu'=>$menu, 'title' => $this->title, 'name'=>$admin->name])->render();

        $this->vars['navigation'] = $navigation;

        if ($this->content){

            $this->vars['content'] = $this->content;
        }

        return view($this->template)->with($this->vars);
    }

    public function getMenu(){

//        return Menu::make('adminMenu', function ($menu){
//
//            $menu->add('News', ['route' => 'admin.news.index']);
//            $menu->add('Menu');
//            $menu->add('Users', ['route' => 'admin.users.index']);
//            $menu->add('Comments', ['route' => 'admin.comments.index']);
//        });

        $menu = collect([

            'News' => 'admin.news.index',
            'Users' => 'admin.users.index',
            'Comments'=> 'admin.comments.index',
            'Menu'=> 'admin.menu.index',
            'Permissions'=> 'admin.permissions.index',
            'Pages'=> 'admin.pages.index',
            'Sidebars'=>'admin.sidebar.index'

            ]);
        return $menu;

    }



}

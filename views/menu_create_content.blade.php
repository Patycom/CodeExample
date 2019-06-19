@if ($errors->any())
    <div class="container alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="container mt-5 ">

    {!! Form::open(['url' => (isset($menu->id)) ? route('admin.menu.update',['id'=>$menu->id]) : route('admin.menu.store'), 'class'=>'form', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
    @csrf


    <div class="container-fluid ">
        <div class="h3 text-center" style="color: #343a40;" >
            Link name :
        </div>

        <div class="row mt-3">

            <div class="col">
                <span class="h6">Menu title :</span><br>
                {!! Form::text('title', isset($menu->title) ? $menu->title : old('title'),[  'placeholder' =>'Enter title', 'style' =>'width:100%;']) !!}
            </div>

            <div class="col ">
                <span class="h6">Menu description :</span><br>
                {!! Form::text('description', isset($menu->description) ? $menu->description : old('description'),[  'placeholder' =>'Enter description', 'style' =>'width:100%;']) !!}
            </div>

        </div>

        <div class="mt-3">
            <span class="h6">Parent item :</span><br>
            {!! Form::select('parent_id',isset($menus)? $menus : $menu, isset($menu->parent_id) && $menu->parent_id !=0 ? $menu->parent_id : null, ['style' =>'width:100%;']) !!}
        </div>

    </div>


    <div class="container-fluid mt-5">

        <div class="h3 text-center" style="color: #343a40;" >
            Link type :
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="" style="color: olive;">
                    {!! Form::radio('type', 1, true ) !!}
                    <span class="h5">Custom link :</span>
                </div>
                <div class="mt-1">
                    <span class="h6">Link path :</span>
                    {!! Form::text('custom_link', isset($menu->path) ? $menu->path : old('path'),[  'placeholder' =>'Enter path', 'style' =>'width:100%;']) !!}
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col">
                <div class="" style="color: olive;">
                    {!! Form::radio('type', 2, false ) !!}
                    <span class="h5">Category or material link :</span>
                </div>
                <div class="row mt-1">
                    <div class="col">
                        <span  class="h6">Category :</span>
                        {!! Form::select('category',$categoryList, isset($menu->parent) ? $menu->parent : null, ['style' =>'width:100%;', 'onchange' => "onchangeSelect()", 'id'=>'categorySelect']) !!}

                    </div>
                    <div class="col">
                        <span class="h6">Material :</span>

                        <select id="materialSelect" name="material_alias" style="width: 100%" disabled></select>
{{--                        {!! Form::select('parent_material',$news, isset($menu->parent) ? $menu->parent : null, ['style' =>'width:100%;', 'id'=>'materialSelect']) !!}--}}
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if(isset($menu->id))
        {{method_field('PUT')}}
    @endif

    <div class="row justify-content-center">
        {!! Form::button('Save', ['class'=>'btn btn-outline-success btn-lg btn-block mt-5 mb-5', 'type'=>'submit', 'style'=>'width:95%;']) !!}
    </div>

    {!! Form::close() !!}


</div>

@if($news)

    <div class="container-fluid mt-5">

        {!! Form::open(['url' => (isset($news->id)) ? route('admin.news.update',['news'=>$news->alias]) : route('admin.news.store'), 'class'=>'form', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}

                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 150px;" id="name">News Name</span>
                    </div>
                    {!! Form::text('name', isset($news->name) ? $news->name : old('name'),[ 'style' =>'width:90%;']) !!}

                </div>

                <div class="input-group mt-3">

                    <div class="input-group-prepend text-right">
                        <span class="input-group-text text-right " style="width: 150px;" id="basic-addon1" >News Alias</span>
                    </div>

                    {!! Form::text('alias', isset($news->alias) ? $news->alias : old('alias'),[  'style' =>'width:90%;']) !!}

                </div>

                <div class="input-group mt-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 150px;" id="basic-addon1">Short Story</span>
                    </div>

                    {!! Form::textarea('short_story', isset($news->short_story) ? $news->short_story : old('short_story'),[ 'style' =>'width:90%;', 'id' => 'editor']) !!}

                </div>

                <div class="input-group mt-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 150px;" id="basic-addon1">Full Story</span>
                    </div>

                    {!! Form::textarea('full_story', isset($news->full_story) ? $news->full_story : old('full_story'),[ 'style' =>'width:90%;', 'id' => 'editor2']) !!}

                </div>

                <div class="input-group mt-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text mr-2" style="width: 150px;" id="basic-addon1">Small Image</span>
                    </div>

                    <div class="col-4">
                        <div class="">
                            {!! Form::file('img',[ 'id'=>'file', 'class'=>'p-1']) !!}

                        </div>

                    </div>
                    {{--{!! Form::file('images', ['data-buttonText'=>'Choose', 'data-buttonName' => 'btn-primary']) !!}--}}

                    @if(!empty($news->small_img))


                        <span style="border-left: 2px solid black;padding: 0 0 0 10px;"><strong>Current image :</strong><br> {{$news->small_img}}   </span>

                        <div style="margin-left: 20px;">

                        {{ Html::image(asset(env('THEME')).'/'.$news->small_img, 'alt', array( 'width' => 200, 'height' => 150 )) }}

                        </div>
                    @endif


                </div>

                <div class="input-group mt-3">

                    <div class="input-group-prepend">
                        <span class="input-group-text mr-2" style="width: 150px;" id="basic-addon1">Category</span>
                    </div>

                    {!! Form::select('cat_id', $categories, isset($news->cat_id) ? $news->cat_id : '') !!}

                </div>

            @if($news->id)

                {{method_field('PUT')}}

{{--                <input type="hidden" name="_method" value="PUT">--}}
            @endif

                <div class="">
                    {!! Form::button('Save', ['class'=>'btn btn-success btn-lg btn-block mt-5 mb-5', 'type'=>'submit']) !!}
                </div>


        {!! Form::close() !!}

        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>


        <script>
            ClassicEditor
                .create( document.querySelector( '#editor2 ' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>


    </div>




@endif
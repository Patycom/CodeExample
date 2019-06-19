@if($news)

    <div class="container-fluid justify-content-center mt-3 " >

        <div class=" text-left ">

            {!! Form::open(['url'=>route('admin.news.create'),'class'=>'form ','method'=>'GET'])!!}

            {!! Form::button('Add news',['class'=>'btn btn-outline-success ', 'type'=>'submit', 'style'=>'width: 150px;'] )!!}

            {!! Form::close() !!}
        </div>


        <div class="row" style="">

{{--            <div class="col-1"></div>--}}
            <div class="col text-center">

                <table class="table-bordered mt-3 table-hover" style="width: 100%; ">

                    <thead class="bg-dark text-light">

                        <tr>
                            <th style="padding: 7px;">#</th>
                            <th style="padding: 7px;">Name</th>
                            <th style="padding: 7px;">Alias</th>
                            <th style="padding: 7px;">Short story</th>
                            <th style="padding: 7px;">Image</th>
                            <th style="padding: 7px;">Category</th>
                            <th style="padding: 7px;">Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($news as $key => $row)

                            <tr>
                                <td style="padding: 15px;">
                                    {{$key + 1}}
                                </td>

                                <td style="padding: 7px;">
                                    {{$row->name}}
                                </td>

                                <td style="padding: 7px;">
                                    {!! Html::link(route('admin.news.edit',['news'=>$row->alias]), $row->alias) !!}
                                </td>

                                <td>
                                    {{substr($row->short_story,0,200)}}
                                </td>

                                <td style="padding: 7px;">
                                    {{ Html::image(asset(env('THEME')).'/'.$row->small_img, 'alt', array( 'width' => 150, 'height' => 100 )) }}

                                </td>

                                <td style="padding: 7px;">
                                    {{$row->category->title}}
                                </td>

                                <td style="padding: 5px 7px 5px 7px;">

                                    {!! Form::open(['url'=>route('admin.news.destroy',['news'=>$row->alias]),'class'=>'form','method'=>'POST'])!!}
                                    {{method_field('DELETE')}}
                                    {!! Form::button('Delete',['class'=>'btn btn-danger', 'type'=>'submit']) !!}
                                    {!! Form::close() !!}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
                <!--Pagination block--->

                <div class="paginator mt-5 bg-dark" style="margin-left: 45%;">

                    {{ $news->links() }}

                </div>

                <!--/Pagination block--->

            </div>


            </div>
{{--            <div class="col-1"></div>--}}
        </div>
    </div>

@endif
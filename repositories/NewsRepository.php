<?php

    namespace App\Repositories;

    use Symfony\Component\HttpFoundation\File\UploadedFile;

    use App\News;

    class NewsRepository extends Repository
    {
        public function __construct(News $news) {

            $this->model = $news;
        }

        public function addNews($request){

            $data = $request->except('_token', 'img');


            if (empty($data)){

                return ['error' => 'No data'];
            }

            if (empty($data['alias'])) {

                $data['alias'] = $this->transliterate($data['name']);
            }

            $file = $request->file('img');

            $filePath = $file->storeAs('/assets/images', $data['alias'].'-'.date('d-m-Y').'.'.$file->extension(),'local' );

            $data['small_img'] = $filePath;
            $data['big_img'] = $filePath;

            $this->model->create($data);

            return $result = 'CREATED';

        }

        public function updateNews($request, $post){

            $data = $request->except('_token', 'img', '_method');


            if (empty($data)){

                return ['error' => 'No data'];
            }

            if (empty($data['alias'])) {

                $data['alias'] = $this->transliterate($data['name']);
            }

            $file = $request->file('img');


            if ($file) {

                $filePath = $file->storeAs('/assets/images', $data['alias'] . '-' . date('d-m-Y') . '.' . $file->extension(), 'local');

                $data['small_img'] = $filePath;
                $data['big_img'] = $filePath;
            }

            $post->update($data);

            return $result = 'EDITED';
        }

        public function deleteNews($post){

            $post->delete();

            return $result = 'DELETED';
        }
    }
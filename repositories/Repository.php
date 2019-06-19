<?php

    namespace App\Repositories;

    use Config;

    abstract class  Repository
    {

        protected $model = FALSE;


        public function get($select = '*', $take = FALSE, $pagination = FALSE, $where = FALSE, $paginationCount = FALSE){

            $builder = $this->model->select($select);

            if ($take){

                $builder->take($take);
            }

            if ($where) {

                $builder->where($where[0],$where[1]);
            }

            if ($pagination) {

                if ($paginationCount != false) return $builder->paginate($paginationCount);

                return $builder->paginate(Config::get('settings.pagination'));
            }

            return $builder->get($take);
        }


        public function one($alias){

            $result = $this->model->where('alias', $alias)->first()->load('comments');
            return $result;
        }


        public function  transliterate($string){

            $str = mb_strtolower($string, 'UTF-8');

            $letters = [

                "а"=>"a",
                "б"=>"b",
                "в"=>"v",
                "г"=>"g",
                "д"=>"d",
                "е,"=>"e",
                "ё"=>"yo",
                "ж"=>"zh",
                "з"=>"z",
                "и"=>"i",
                "й"=>"j",
                "к"=>"k",
                "л"=>"l",
                "м"=>"m",
                "н"=>"n",
                "о"=>"o",
                "п"=>"p",
                "р"=>"r",
                "с"=>"s",
                "т"=>"t",
                "у"=>"u",
                "ф"=>"f",
                "х"=>"kh",
                "ц"=>"ts",
                "ч"=>"ch",
                "ш"=>"sh",
                "щ"=>"sch",
                "ъ"=>"",
                "ы"=>"y",
                "ь"=>"",
                "э"=>"e",
                "ю"=>"yu",
                "я"=>"ya",
            ];

            foreach ($letters as $cyr => $lat) {

                $str = str_replace($cyr, $lat, $str);
            }

            $str = preg_replace('/(\s|[^A-za-z0-9\-])+/','_',$str);

            $str = trim($str,'-');

            return $str;
        }




    }
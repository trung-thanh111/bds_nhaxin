<?php

namespace App\Traits;

trait QueryScopes
{
    public function scopeKeyword($query, $keyword, $fieldSearch = [], $whereHas = []){
        if(!empty($keyword)){
            $keywords = is_array($keyword) ? $keyword : [$keyword];
            $query->where(function($q) use ($keywords, $fieldSearch) {
                foreach($keywords as $word) {
                    $q->where(function($subQ) use ($word, $fieldSearch) {
                        if(count($fieldSearch)){
                            foreach($fieldSearch as $val){
                                $subQ->orWhere($val, 'LIKE', '%'.$word.'%');
                            }
                        } else {
                            $subQ->where('name', 'LIKE', '%'.$word.'%');
                        }
                    });
                }
            });
        }
        
        if(isset($whereHas) && count($whereHas)){
            $field = $whereHas['field'];
            $keywords = is_array($keyword) ? $keyword : [$keyword];
            $query->orWhereHas($whereHas['relation'], function($q) use ($field, $keywords){
                foreach($keywords as $word) {
                    $q->where($field, 'LIKE', '%'.$word.'%');
                }
            });
        }

        return $query;
    }

    public function scopePublish($query, $publish){
        if(!empty($publish) ){
            $query->where('publish', '=', $publish);
        }
        return $query;
    }

    public function scopeCustomWhere($query, $where = []){
        if(!empty($where)){
            foreach($where as $key => $val){
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }

    public function scopeCustomWhereIn($query, $whereInField = '', $whereIn = []){
        if(!empty($whereInField) && !empty($whereIn)){
            $query->whereIn($whereInField, $whereIn);
        }
        return $query;
    }

    public function scopeCustomWhereRaw($query , $rawQuery){
        if(is_array($rawQuery) && !empty($rawQuery)){
            foreach($rawQuery as $key => $val){
                $query->whereRaw($val[0], $val[1]);
            }
        }
        return $query;
    }

    public function scopeRelationCount($query, $relation){
        if(!empty($relation)){
            foreach($relation as $key => $item){
                $relationName = is_string($key) ? $key : $item;
                if (is_string($relationName) && !str_contains($relationName, '.')) {
                    $query->withCount($relationName);
                }
                $query->with([$key => $item]);
            }
        }
        return $query;
    }

    public function scopeRelation($query, $relation){
        if(!empty($relation)){
            foreach($relation as $relation){
                $query->with($relation);
            }
        }
        return $query;
    }

    public function scopeCustomJoin($query, $join){
        if(!empty($join)){
            foreach($join as $key => $val){
                $query->join($val[0], $val[1], $val[2], $val[3], $val[4] ?? 'inner');
            }
        }
        return $query;
    }

    public function scopeCustomGroupBy($query, $groupBy){
        if(!empty($groupBy)){
            if(is_array($groupBy)){
                $groupBy = array_map(function($item){
                    return preg_replace('/\s+as\s+.+$/i', '', $item);
                }, $groupBy);
            }
            $query->groupBy($groupBy);
        }
        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy){
        if(isset($orderBy) && !empty($orderBy)){
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }


    public function scopeCustomDropdownFilter($query, $condition){
        if(count($condition)){
            foreach($condition as $key => $val){
                if($val != 'none' && !empty($val) && $val != ''){
                    $query->where($key, '=', $val);
                }
            }
        }
        return $query;
    }

    public function scopeCustomerCreatedAt($query, $condition){
        if(!empty($condition)){
            $explode = explode('-', $condition);
            $explode = array_map('trim', $explode);
            $startDate = convertDateTime($explode[0], 'Y-m-d 00:00:00', 'm/d/Y');
            $endDate = convertDateTime($explode[1], 'Y-m-d 23:59:59', 'm/d/Y');

           $query->whereDate('created_at', '>=', $startDate);
           $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }

}

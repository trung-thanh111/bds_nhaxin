<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Services\V2\BaseService;
use App\Repositories\RealEstate\GalleryCatalogueRepo;
use Illuminate\Support\Facades\Auth;

class GalleryCatalogueService extends BaseService
{

    protected $repository;

    protected $fillable;

    protected $with = ['languages']; // Adjusted default relations to fetch

    public function __construct(
        GalleryCatalogueRepo $repository
    ) {
        $this->repository = $repository;
    }

    public function prepareModelData(): static
    {
        $request = $this->context['request'] ?? null;
        if (!is_null($request)) {
            $this->fillable = $this->repository->getFillable();
            $this->modelData = $request->only($this->fillable);
            $this->modelData['user_id'] = Auth::id();
        }
        return $this;
    }

    public function afterSave(): static
    {
        parent::afterSave();
        $request = $this->context['request'] ?? null;
        if (!is_null($request) && isset($this->context['action'])) {
            $this->updateLanguage($request);
        }
        return $this;
    }

    protected function updateLanguage($request)
    {
        $languageId = $request->input('language_id', 1); // fallback to language_id 1 when null
        $this->model->languages()->syncWithoutDetaching([
            $languageId => [
                'name' => $request->input('name', ''),
                'description' => $request->input('description', ''),
                'content' => $request->input('content', ''),
                'meta_title' => $request->input('meta_title', ''),
                'meta_keyword' => $request->input('meta_keyword', ''),
                'meta_description' => $request->input('meta_description', ''),
                'canonical' => \Illuminate\Support\Str::slug($request->input('name', '')),
            ]
        ]);
    }
}

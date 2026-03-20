<?php

namespace App\Services\V1\RealEstate;

use App\Services\V1\BaseService;
use App\Repositories\RealEstate\FloorplanRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FloorplanService extends BaseService
{
    protected $floorplanRepository;

    public function __construct(
        FloorplanRepository $floorplanRepository
    ){
        $this->floorplanRepository = $floorplanRepository;
    }

    public function paginate($request, $languageId){
        $perPage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $languageId],
            ]
        ];
        $floorplans = $this->floorplanRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perPage,
            ['path' => 'real/estate/floorplan/index'],  
            ['floorplans.id', 'DESC'],
            [
                ['floorplan_language as tb2','tb2.floorplan_id', '=' , 'floorplans.id'],
                [DB::raw('(SELECT real_estate_id, name, language_id FROM real_estate_language WHERE language_id = '.$languageId.') as re_lang'), 're_lang.real_estate_id', '=', 'floorplans.real_estate_id', 'left']
            ], 
            ['languages']
        );

        return $floorplans;
    }

    public function create($request, $languageId){
        DB::beginTransaction();
        try{
            $floorplan = $this->createFloorplan($request);
            if($floorplan->id > 0){
                $this->updateLanguageForFloorplan($floorplan, $request, $languageId);
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update($id, $request, $languageId){
        DB::beginTransaction();
        try{
            $floorplan = $this->floorplanRepository->findById($id);
            $flag = $this->updateFloorplan($floorplan, $request);
            if($flag == TRUE){
                $this->updateLanguageForFloorplan($floorplan, $request, $languageId);
            }
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $this->floorplanRepository->delete($id);
            DB::commit();
            return true;
        }catch(\Exception $e ){
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function createFloorplan($request){
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $floorplan = $this->floorplanRepository->create($payload);
        return $floorplan;
    }

    private function updateFloorplan($floorplan, $request){
        $payload = $request->only($this->payload());
        $flag = $this->floorplanRepository->update($floorplan->id, $payload);
        return $flag;
    }

    private function updateLanguageForFloorplan($floorplan, $request, $languageId){
        $payload = $this->formatLanguagePayload($floorplan, $request, $languageId);
        $floorplan->languages()->detach([$languageId, $floorplan->id]);
        $language = $this->floorplanRepository->createPivot($floorplan, $payload, 'languages');
        return $language;
    }

    private function formatLanguagePayload($floorplan, $request, $languageId){
        $payload = $request->only($this->payloadLanguage());
        $payload['language_id'] =  $languageId;
        $payload['floorplan_id'] = $floorplan->id;
        return $payload;
    }

    private function paginateSelect(){
        return [
            'floorplans.id', 
            'floorplans.real_estate_id',
            'floorplans.publish',
            'floorplans.image',
            'tb2.name as name', 
            're_lang.name as real_estate_name',
        ];
    }

    private function payload(){
        return [
            'real_estate_id',
            'publish',
            'image',
            'order',
        ];
    }

    private function payloadLanguage(){
        return [
            'name',
            'description',
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourceId = 74;
        $sourceProject = Project::find($sourceId);

        if (!$sourceProject) {
            $this->command->error("Project with ID {$sourceId} not found as a sample.");
            return;
        }

        $amenityIds = $sourceProject->amenities()->pluck('amenities.id')->toArray();

        $fieldsToCopy = [
            'apartment_count',
            'block_count',
            'area',
            'legal_status',
            'province_code',
            'province_name',
            'district_code',
            'district_name',
            'ward_code',
            'ward_name',
            'old_province_code',
            'old_province_name',
            'old_district_code',
            'old_district_name',
            'old_ward_code',
            'old_ward_name',
            'street',
            'iframe_map',
        ];

        $dataToUpdate = [];
        foreach ($fieldsToCopy as $field) {
            $dataToUpdate[$field] = $sourceProject->$field;
        }

        $targetProjects = Project::where('id', '!=', $sourceId)->get();

        $this->command->info("Updating " . $targetProjects->count() . " projects based on ID {$sourceId}...");

        foreach ($targetProjects as $project) {
            $project->update($dataToUpdate);

            $project->amenities()->sync($amenityIds);
        }

        $this->command->info('ProjectUpdateSeeder completed successfully.');
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Feature;
use Illuminate\Http\Request;
use App\Imports\FeaturesImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\HeadingRowImport;
use App\Repositories\Feature\FeatureRepositoryInterface;

class FeatureAPIController extends Controller
{
    //
    protected $featureRepo;

    public function __construct(FeatureRepositoryInterface $featureRepo)
    {
        $this->featureRepo = $featureRepo;
    }

    public function getFeatureData()
    {
        $features = $this->featureRepo->listAllData();
        ResponseData($features);
    }
    public function getFeatureByDepartment($departmentId){
        $features = $this->featureRepo->gtFeatureByDepartment($departmentId);
        ResponseData($features);
    }

    public function featureImport(Request $request)
    {
        $file = $request->file('feature_import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'module',
            'name',
            'slug'
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $featureImport = new FeaturesImport();
        $featureImport->import($file);
        return ResponseMessage('Feature Import Successfully', 200);
    }
}

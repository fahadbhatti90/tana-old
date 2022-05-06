<?php

namespace App\Http\Controllers;

use App\Model\Vendors;
use App\Model\Category;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Helper;
use DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,9')->only(['index']);
        $this->middleware('permission:2,9')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        setMemoryLimitAndExeTime();
        $validator = Validator::make($request->all(), ['categoryFile' => 'required']);
        $responseData = array();
        $errorMessage = array();
        $successMessage = array();
        // If validation Passes e.g no errors
        if ($validator->passes()) {
            // get Extension
            $fileExtension = ($request->hasFile('categoryFile') ? $request->file('categoryFile')->getClientOriginalExtension() : '');
            //dd($fileExtension);
            // Validate upload file
            if ($this->validateExcelFile($fileExtension) != false) {

                // get sales data After Read File
                $categoryData = getCategoryDataFromExcelFile($request->file('categoryFile'), 'category');
                // check if sales Data not empty
                if (!empty($categoryData)) {
                    if (isset($categoryData[0]['asin']) && isset($categoryData[0]['category']) && isset($categoryData[0]['vendor'])) {
                        $storeCategoryData = []; // define array for Store Data into DB
                        //$storeAsinsScProduct = []; // define array for Store Data into SC Product DB
                        $dbData = [];
                        // $asinsToStore = [];
                        // $cronJobList = [];
                        // make array to insert data
                        foreach ($categoryData as $data) {
                            $dbData = $this->dailySalesData($data);
                            $dbData['inserted_at'] = date('Y-m-d');
                            // $dbData['updated_at'] = date('Y-m-d h:i:s');
                            // making array for insertion in daily sales
                            array_push($storeCategoryData, $dbData);
                        } // End for each Loop

                        if (!empty($storeCategoryData)) {
                            // subArraysToString($storeDailySalesData);
                            foreach (array_chunk($storeCategoryData, 1000) as $t) {
                                $category = Category::Insertion($t);
                            }
                        }
                        unset($salesData);
                        unset($storeCategoryData);
                        unset($dbData);
                        // $request->session()->put('fk_vendor_id', $fkVendorId);
                        $responseData = array('success' => 'You have successfully uploaded Report!', 'ajax_status' => true);
                    } else {
                        $errorMessage = array('File is not valid kindly upload Category file');
                        $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                    } // End condition of if else
                } else {
                    $errorMessage = array('File is empty kindly upload updated file');
                    $responseData = array('error' => $errorMessage, 'ajax_status' => false);
                } // End condition of if else
            } else {
                $errorMessage = array('File extension should be csv, xls or xlsx');
                $responseData = array('error' => $errorMessage, 'ajax_status' => false);
            }
        } else {
            $responseData = array('error' => $validator->errors()->all(), 'ajax_status' => false);
        } // End condition of if else of checking validations
        return response()->json($responseData);
    }
    /**
     *  This function is used to gather Daily Sales Data
     * @param $data
     * @return array
     */
    private function dailySalesData($data)
    {
        $dbData = array();
        $dbData['asin'] = (isset($data['asin']) && !empty($data['asin']) ? $data['asin'] : 'NA');
        $dbData['category'] = (isset($data['category']) && !empty($data['category']) ? $data['category'] : 'NA');
        $dbData['fk_vendor_name'] = (isset($data['vendor']) && !empty($data['vendor']) ? $data['vendor'] : 'NA');
        return $dbData;
    }



    function validateExcelFile($file_ext)
    {
        $valid = array(
            'csv', 'xls', 'xlsx' // add your extensions here.
        );
        return in_array($file_ext, $valid) ? true : false;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

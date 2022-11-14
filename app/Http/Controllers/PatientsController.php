<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use App\Models\Status;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class PatientsController extends Controller
{
    public function index()
    {
        // menampilkan data dengan memanggil model Patients dengan relation status
        $data = Patients::with('status_patient')->get();

        // cek apakah data ada?
        if($data->count() > 0){ 
            // jika data lebih dari kosong/(0) maka akan menampilkan data berserta pesan dan code statusnya
            $res['message'] = 'Get All Resource';
            $res['data'] = $data;
            
            return response()->json($res,200);

        }else{ 
            // jika data kurang dari satu / sama dengan kosong/(0) maka akan menampilkan pesan beserta code statusnya
            $res['message'] = 'Data Is Empty';

            return response()->json($res,200);
        }
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
        // membuat peraturan validasi
        $rules = [
            'name' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'status_id' => 'required',
            'in_date_at' => 'required|date_format:Y-m-d',
        ];
        
        // menginputkan data
        $data['name'] = $request->name;
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['status_id'] = $request->status_id;
        $data['in_date_at'] = $request->in_date_at;
        $data['out_date_at']=null;
        
        // malakukan validasi data
        $validator = Validator::make($data,$rules);

        // pengecekan validasi
        if($validator->fails()){
            // jika validasi gagal / error maka akan menampilkan pesan error beserta code statusnya

            // validasi error
            $errors = $validator->errors();

            // menampilkan pesan error beserta code statusnya
            $res['message'] = $errors;
            return response()->json($res,411);
        }else{
            // jika validasi berhasil / success maka akan menyimpan data ke dalam database dan menampilkan data beserta pesan dan code statusnya

            // proses minyimpan data ke database
            Patients::create($data);

            // menampilkan pesan berserta isi data yang diinput dan code statusnya
            $res['message'] = 'Resource is added successfully';
            $res['data'] = $data;
            return response()->json($res,201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // menampilkan data dengan memanggil model Patient dengan relation status dengan id yang ditentukan
        $data = Patients::with('status_patient')->find($id);

        // cek apakah data ada?
        if($data != ''){
            // jika data tidak kosong maka akan menampilkan pesan beserta data dan code statusnya
            $res['message'] = 'Get Detail Resource';
            $res['data'] = $data;
            return response()->json($res,200);
        }else{
            // jika data kosong maka akan menampilkan pesan beserta  code statusnya
            $res['message'] = 'Resource not found';
            return response()->json($res,404);
        }
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
        $body = $request->All();
        // membuat peraturan validasi
        $rules = [
            'phone' => 'numeric',
            'in_date_at' => 'date_format:Y-m-d',
        ];

        // get data dengan id sebagai parameternya
        $data_patient = Patients::find($id);
        // get / menangkap data dari request
        $data['name'] = $request->name ?? $data_patient->name;
        $data['phone'] = $request->phone ?? $data_patient->phone;
        $data['address'] = $request->address;
        $data['status_id'] = $request->status_id;
        $data['in_date_at'] = $request->in_date_at ?? $data_patient->in_date_at;
        $data['out_date_at'] = $request->out_date_at;

        // cek apakah data ada?
        if($data_patient != ''){ // jika data tidak kosong lanjut proses selanjutnya yaitu proses validasi

            // menjalankan validasi
            $validator = Validator::make($data,$rules);
            // pengecekan validasi
            if($validator->fails()){
                // jika validasi gagal / error maka akan menampilkan pesan error beserta code statusnya

                // validasi error
                $errors = $validator->errors();

                // menampilkan pesan error beserta code statusnya
                $res['message'] = $errors;
                return response()->json($res,411);
            }else{
                // data pasien diupdate
                $data_patient->update($data);
                // menampilkan pesan berserta isi data yang diinput dan code statusnya
                $res['message'] = 'Resource is update successfully';
                $res['data'] = $data;
                return response()->json($res,200);
            }
        }else{ // jika data kosong maka akan menampilkan pesan beserta code statusnya
            $res['message'] = 'Resource Not Found';
            return response()->json($res,200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // menampilkan data dengan memanggil model Patient dengan id yang ditentukan
        $data = Patients::find($id);

        // cek apakah data ada?
        if($data != ''){
            // jika data tidak kosong 
            // menjalankan fungsi delete() / proses menghapus data di dalam database 
            $data->delete();
            // menampilkan pesan beserta code statusnya
            $res['message'] = 'Resource is delete successfully';
            return response()->json($res,200);
        }else{
            // jika data kosong
            // menampilkan pesan beserta code statusnya
            $res['message'] = 'Resource not found';
            return response()->json($res,404);
        }
    }

    public function search($name)
    {   
        // menampilkan / get data pasien berdasarkan parameter $name
        $data = Patients::where('name','like','%'.$name.'%')
        ->with('status_patient')->get();

        // cek apakah data ada?
        if($data->count() > 0)
        { // jika data lebih dari kosong(0) maka akan menampilkan pesan beserta data pasient berdasarkan parameter $name dan code statusnya
            $res['message'] = 'Get searched resource';
            $res['data'] = $data;

            return response()->json($res,200);
        }else{
            // jika data kosong(0) maka akan menampilkan pesan beserta code statusnya
            $res['message'] = "Resource not found";
            return response()->json($res,404);
        }
    }

    public function positive($status = 'Positive')
    {
        // get data status_patients berdasarkan parameter $status
        $status = Status::where('status',$status)->first();

        // cek apakah data dengan parameter $status ada ?
        if($status != '')
        { //jika data status ada maka lanjut proses selanjutnya
            // menampilkan data patien dengan model Patient berdasarkan status_id
            $data_patient = Patients::where('status_id',$status->id_status)->get();
            // melakukan pengecekkan kembali apakah data patient ada?
            if($data_patient->count() > 0){
                // jika ada maka menampilkan total / jumlah patient dengan data patient berdasarkan statusnya
                $res['total'] = $data_patient->count()." Patient";
                $res['data'] = Status::with('patient')
                ->select(
                    'id_status',
                    'status'
                )
                ->where('status',$status->status)
                ->get();

                return response()->json($res,200);
            }else{
                $res['total'] = $data_patient->count()." Patient";
                $res['data'] = ['patient'=>'Patient is empty in status '.$status->status];

                return response()->json($res,200);
            }
        }else{
            $res['message'] = "Status not found";
            return response()->json($res,404);
        }
    }

    public function recovered($status = 'Recovered')
    {
        // get data status_patients berdasarkan parameter $status
        $status = Status::where('status',$status)->first();

        // cek apakah data dengan parameter $status ada ?
        if($status != '')
        { //jika data status ada maka lanjut proses selanjutnya
            // menampilkan data patien dengan model Patient berdasarkan status_id
            $data_patient = Patients::where('status_id',$status->id_status)->get();
            // dd($data_patient);
            // melakukan pengecekkan kembali apakah data patient ada?
            if($data_patient->count() > 0){
                // jika ada maka menampilkan total / jumlah patient dengan data patient berdasarkan statusnya
                $res['total'] = $data_patient->count()." Patient";

                // $res['data'] = Status::with('patient')
                // ->select(
                //     'id_status',
                //     'status'
                // )
                // ->where('status',$status->status)
                // ->get();
                $res['id'] = $status->id_status;
                $res['status'] = $status->status;
                $res['data'] = $data_patient;
                // dd($res);
                return response()->json($res,200);
            }else{
                // jika data patient kosong
                // maka akan menampilkan total / jumlah pasien beserta pesan dan status codenya
                $res['total'] = $data_patient->count()." Patient";
                $res['data'] = ['patient'=>'Patient is empty in status '.$status->status];

                return response()->json($res,200);
            }
        }else{
            // jika data patient kosong
            // maka akan menampilkan total / jumlah pasien beserta pesan dan status codenya
            $res['message'] = "Status not found";
            return response()->json($res,404);
        }
    }

    public function dead($status = 'Dead')
    {
        // get data status_patients berdasarkan parameter $status
        $status = Status::where('status',$status)->first();

        // cek apakah data dengan parameter $status ada ?
        if($status != '')
        { //jika data status ada maka lanjut proses selanjutnya
            // menampilkan data patien dengan model Patient berdasarkan status_id
            $data_patient = Patients::where('status_id',$status->id_status)->get();
            // dd($data_patient);
            // melakukan pengecekkan kembali apakah data patient ada?
            if($data_patient->count() > 0){
                // jika ada maka menampilkan total / jumlah patient dengan data patient berdasarkan statusnya
                $res['total'] = $data_patient->count()." Patient";

                $res['data'] = Status::with('patient')
                ->select(
                    'id_status',
                    'status'
                )
                ->where('status',$status->status)
                ->get();
                return response()->json($res,200);
            }else{
                // jika data patient kosong
                // maka akan menampilkan total / jumlah pasien beserta pesan dan status codenya
                $res['total'] = $data_patient->count()." Patient";
                $res['data'] = ['patient'=>'Patient is empty in status '.$status->status];

                return response()->json($res,200);
            }
        }else{
            // jika data patient kosong
            // maka akan menampilkan total / jumlah pasien beserta pesan dan status codenya
            $res['message'] = "Status not found";
            return response()->json($res,404);
        }
    }

    public function status($status)
    {
        // get data status_patients berdasarkan parameter $status
        $status = Status::where('status',$status)->first();

        // cek apakah data dengan parameter $status ada ?
        if($status != '')
        { //jika data status ada maka lanjut proses selanjutnya
            // menampilkan data patien dengan model Patient berdasarkan status_id
            $data_patient = Patients::where('status_id',$status->id)->get();
            // melakukan pengecekkan kembali apakah data patient ada?
            if($data_patient->count() > 0){
                // jika ada maka menampilkan total / jumlah patient dengan data patient berdasarkan statusnya
                $res['total'] = $data_patient->count()." Patient";

                $res['data'] = Status::with('patient')
                ->select(
                    'id',
                    'status'
                )
                ->where('status',$status->status)
                ->get();

                return response()->json($res,200);
            }else{
                $res['total'] = $data_patient->count()." Patient";
                $res['data'] = ['patient'=>'Patient is empty in status '.$status->status];

                return response()->json($res,200);
            }
        }else{
            $res['message'] = "Status not found";
            return response()->json($res,404);
        }
    }

}

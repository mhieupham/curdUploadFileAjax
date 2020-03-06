<?php

namespace App\Http\Controllers;

use App\CurdAjax;
use Illuminate\Http\Request;
use Validator;
class CurdController extends Controller
{
    //
    function index(){
        return view('index');
    }
    function getajaxindex(){
        $students = CurdAjax::paginate(5);
        return $students;
    }
    function getfetchdata(Request $request){
        $student = CurdAjax::findOrFail($request->input('id'));
        return $student;
    }
    function insertdata(Request $request){
        $success_output ='';
        $errors =[];
        if($request->input('button_action') == 'insert'){
            $validation = Validator::make($request->all(),[
                'first_name'=>'required',
                'last_name'=>'required',
                'image'=>'required|image|mimes:jpeg,jpg,png'
            ]);
            if($validation->fails()){
                $errors=$validation->errors()->all();
            }else{
                $image = $request->file('image');
                $newImage = rand().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('images'),$newImage);
                $student = new CurdAjax([
                    'first_name'=>$request->input('first_name'),
                    'last_name'=>$request->input('last_name'),
                    'image'=>$newImage
                ]);
                $student->save();
                $success_output ='<div class="alert alert-success">Add Success</div>';
            }
        }elseif ($request->input('button_action') == 'edit'){
            $image = $request->file('image');
            if($image == null){
                $rule=[
                    'first_name'=>'required',
                    'last_name'=>'required',
                ];
                $data=[
                    'first_name'=>$request->input('first_name'),
                    'last_name'=>$request->input('last_name')
                ];
                $validation = Validator::make($data,$rule);
                if($validation->fails()){
                    $errors=$validation->errors()->all();
                }else{
                    $student = CurdAjax::findOrFail($request->input('student_id'));
                    $student->first_name= $request->input('first_name');
                    $student->last_name=$request->input('last_name');
                    $student->save();
                    $success_output ='<div class="alert alert-success">Edit Success</div>';
                }
            }else{
                $rule=[
                    'first_name'=>'required',
                    'last_name'=>'required',
                    'image'=>'required|image|mimes:jpeg,png,jpg'
                ];
                $data=[
                    'first_name'=>$request->input('first_name'),
                    'last_name'=>$request->input('last_name'),
                    'image'=>$request->file('image')
                ];
                $validation = Validator::make($data,$rule);
                if($validation->fails()){
                    $errors=$validation->errors()->all();
                }else{
                    $newImage = rand().'.'.$image->getClientOriginalExtension();
                    $image->move(public_path('images'),$newImage);
                    $student = CurdAjax::findOrFail($request->input('student_id'));
                    $student->first_name = $request->input('first_name');
                    $student->last_name = $request->input('last_name');
                    $student->image = $newImage;
                    $student->save();
                    $success_output ='<div class="alert alert-success">Edit Success</div>';
                }
            }

        }
        $output = array(
            'error'=>$errors,
            'success'=>$success_output
        );
        echo json_encode($output);

    }
    function destroydata(Request $request){
        $student = CurdAjax::findOrFail($request->input('id'));
        $student->delete();
        $success_output = 'Delete Success';
        echo json_encode($success_output);
    }
}

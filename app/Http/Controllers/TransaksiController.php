<?php

namespace App\Http\Controllers;
use App\Transaksi;
use App\User;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class TransaksiController extends Controller
{
    public function index()
    {
        $data = Transaksi::all();
        return $data;
    }

    public function store(Request $request)
    {
        try{
            if (! $akun = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
            $data = new Transaksi();
            $data->username = $akun['username']; 
            $data->jenis =$request->input('jenis');
            $data->nama_transaksi =$request->input('nama_transaksi');
            $data->jumlah =$request->input('jumlah');
            $data->save(); 

            $user = User::where('id',$akun['id'])->first();
            $user->saldo =$user->saldo - $request->input('jumlah');
            $user->save();

            return response()->json(compact('data','user'));
        } catch(\Exception $e){
            return response()->json([
                'status' => '0', 'message' => 'Ra kenek cok' 
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        try{
            if (! $akun = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
            $data = new Transaksi();
            $data->username = $akun['username'];   
            $data->jenis =$request->input('jenis');
            $data->nama_transaksi =$request->input('nama_transaksi');
            $data->jumlah =$request->input('jumlah');
            $data->save(); 

            $user = User::where('id',$akun['id'])->first();
            $user->saldo =$user->saldo + $request->input('jumlah');
            $user->save();

            return response()->json(compact('data','user'));
        } catch(\Exception $e){
            return response()->json([
                'status' => '0', 'message' => 'Ra kenek cok' 
            ]);
        }
    }
}

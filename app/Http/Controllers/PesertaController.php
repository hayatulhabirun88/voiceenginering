<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Countdown;
use Illuminate\Support\Str;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $peserta = Peserta::all();
        return view('home.peserta', compact(['peserta']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('home.tambah_peserta');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_peserta' => 'required',
            'jenis_kelamin' => 'required',
            'fakultas' => 'required',
            'kategori' => 'required',
            'alamat' => 'required',
            'kode' => 'required',
            'foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $peserta = Peserta::create([
            'nama_peserta'  => $request->nama_peserta,
            'jenis_kelamin' => $request->jenis_kelamin,
            'fakultas'  => $request->fakultas,
            'kategori' => $request->kategori,
            'alamat'        => $request->alamat,
            'kode'        => $request->kode,
            'vote_status' => 'N'
        ]);

        if ($request->file('foto')) {
            $input['foto'] = time() . '.' . $request->file('foto')->getClientOriginalExtension();

            $request->file('foto')->storeAs('public/upload/peserta', $input['foto']);

            $peserta->foto = $input['foto'];
            $peserta->save();
        }


        return redirect('peserta')->with(['success' => 'Data Berhasil Disimpan!']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $peserta = Peserta::findOrFail($id);
        return view('home.edit_peserta', compact(['peserta']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_peserta' => 'required',
            'jenis_kelamin' => 'required',
            'fakultas' => 'required',
            'kategori' => 'required',
            'alamat' => 'required',
            'kode' => 'required',
            'foto' => 'mimes:jpg,jpeg,png|max:500'
        ]);

        $peserta = Peserta::where('id',$id)->first();

        $peserta->nama_peserta  = $request->nama_peserta;
        $peserta->jenis_kelamin = $request->jenis_kelamin;
        $peserta->fakultas      = $request->fakultas;
        $peserta->kategori      = $request->kategori;
        $peserta->alamat        = $request->alamat;
        $peserta->kode          = $request->kode;
        
        if ($peserta->foto && $request->file('foto') != "") {
            $image_path = public_path() . '/storage/upload/peserta/' . $peserta->foto;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        if ($request->file('foto')) {

            $input['foto'] = time() . '.' . $request->file('foto')->getClientOriginalExtension();

            $request->file('foto')->storeAs('public/upload/peserta', $input['foto']);

            $peserta->foto = $input['foto'];
            
        }

        $peserta->save();

        return redirect('peserta')->with(['success' => 'Data Berhasil Update!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);

        if ($peserta['foto'] != NULL) {
            $image_path = public_path() . '/storage/upload/peserta/' . $peserta['foto'];
            unlink($image_path);
        }
        
        //delete peserta
        $peserta->delete();

        //redirect to index
        return redirect('peserta')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function vote_status(Request $request)
    {

        if ($request->vote_status == "Y") {
            $cekpersiapan = Peserta::where('vote_status', 'T')->first();
            if ($cekpersiapan) {
                if($cekpersiapan->id != $request->id){
                    return redirect('dashboard')->with(['error' => 'Vote tidak dapat dilakukan, mohon buka vote yang sudah aktif persiapannya!']);
                } else {
                    $cekpeserta = Peserta::where('vote_status', 'Y')->first();
                    if ($cekpeserta) {
                        $cekpeserta->vote_status = 'N';
                        $cekpeserta->save();
        
                        $currentDate = \Carbon\Carbon::now();
                        $updatedDateTime = $currentDate->addMinutes(2);
        
                        $peserta = Peserta::find($request->id)->update([
                            'vote_status' => 'Y',
                            'countdown' => $updatedDateTime
                        ]);
        
                        return redirect('dashboard')->with(['success' => 'Vote berhasil dibuka!']);
                        // return redirect('dashboard')->with(['error' => 'Terdapat Vote yang masih aktif, silahkan tutup terlebih dahulu ']);
                    } else {
                        $currentDate = \Carbon\Carbon::now();
                        $updatedDateTime = $currentDate->addMinutes(2);
                        $peserta = Peserta::find($request->id)->update([
                            'vote_status' => 'Y',
                            'countdown' => $updatedDateTime
                        ]);
                        return redirect('dashboard')->with(['success' => 'Vote berhasil dibuka!']);
                    }
                } 

            } else {
                return redirect('dashboard')->with(['error' => 'Vote tidak dapat dilakukan, mohon klik persiapan atau tutup vote terlebih dahulu!']);
            }

        } elseif($request->vote_status == "T"){

            $cekpeserta = Peserta::where('vote_status', 'T')->first();
            if ($cekpeserta) {
                $cekpeserta->vote_status = 'N';
                $cekpeserta->save();

                $peserta = Peserta::find($request->id)->update([
                    'vote_status' => 'T',
                ]);
            } else {
                $peserta = Peserta::find($request->id)->update([
                    'vote_status' => 'T',
                ]);
            }
            

            return redirect('dashboard');

            
        }else {
            $peserta = Peserta::find($request->id)->update([
                'vote_status' => 'N'
            ]);
            return redirect('dashboard')->with(['error' => 'Vote  ditutup!']);
        }
        
    }

    public function vote_off(Request $request)
    {
        if ($request->id != NULL || $request->id != "") {
            # code...
            $peserta = Peserta::find($request->id)->update([
                'vote_status' => 'H'
            ]); 
    
            $data = [
                'status' => true,
                'message' => 'Berhasil menutup vote',
                'data' => $peserta
            ];
        } else {

            $data = [
                'status' => false,
                'message' => 'Gagal menutup vote',
            ];

        }

        // Mengirimkan respons JSON
        return response()->json($data);
    }



}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use App\Models\Peserta;

class VoterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voter = Voter::join('peserta', 'voter.peserta_id', '=', 'peserta.id')
                        ->get();
        return view('voter.voter', compact(['voter']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function truncate(){
        // Panggil metode truncate() pada model terkait
        Voter::truncate();

        return redirect('voter')->with(['success' => 'Voter berhasil di kosongkan']);
    }

    public function box_penilaian(){

        $peserta = Peserta::where('vote_status', 'Y')->get();
        $voter = Voter::where('peserta_id', $peserta[0]->id)->get();
        $result = [];
        $jml_nilai = 0;
        foreach ($voter as $vote) {
            $jml_nilai += $vote->nilai;
        }

        if (count($voter) > 0) {
            $nilai = number_format(($jml_nilai / count($voter)), 2);
        } else {
            $nilai = 0;
        }

        $data = [
            'status' => true,
            'data' => [
                'peserta' => $peserta,
                'jml_nilai' => $jml_nilai,
                'jml_vote' => count($voter),
                'nilai' => $nilai
            ]
        ];

        return response()->json($data);
    }
}

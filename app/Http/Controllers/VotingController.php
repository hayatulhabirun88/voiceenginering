<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use App\Models\Peserta;

class VotingController extends Controller
{
    public function index()
    {
        return view('voter.voting');
    }

    public function peserta()
    {
        $peserta = Peserta::where('vote_status', 'Y')->get();
        $persiapan = Peserta::where('vote_status', 'T')->get();
        $hasil = Peserta::where('vote_status', 'H')->get();
        
        if ($peserta->isNotEmpty()) {
            $voter = Voter::where('peserta_id', $peserta[0]->id)->get();
            # code...
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
        }elseif($hasil->isNotEmpty()){
            $voter = Voter::where('peserta_id', $hasil[0]->id)->get();
            # code...
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
                    'peserta' => $hasil,
                    'jml_nilai' => $jml_nilai,
                    'jml_vote' => count($voter),
                    'nilai' => $nilai
                ]
            ];
        } elseif($persiapan->isNotEmpty()) {
            $voter = Voter::where('peserta_id', $persiapan[0]->id)->get();
            # code...
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
                    'peserta' => $persiapan,
                    'jml_nilai' => $jml_nilai,
                    'jml_vote' => count($voter),
                    'nilai' => $nilai
                ]
            ];
        } else {
            $data = [
                'status' => false,
                'data' => [
                    'peserta' => '',
                    'jml_nilai' => 0,
                    'jml_vote' => 0,
                    'nilai' => 0
                ]
            ];
        }

        return response()->json($data);
    }

}

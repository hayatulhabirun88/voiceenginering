@extends('layout.content')
 
@section('title', 'Dashboard')

@section('judul')
<h1 class="mt-4"><a href="/voting" target="_blank" class="btn btn-sm btn-primary">Klik disini untuk melihat halaman voting</a></h1>

@if(count($tutupvote) > 0)
    <p style="color:red;">Harap menutup TOMBOL TUTUP VOTE yang aktif terlebih dahulu, sebelum memulai Klik Persiapan yang Lain!!!</p>
@endif
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"></li>
                        </ol>
@endsection
 
@section('content')

<div id="countdown" class="text-center" style="color:red; font-size:50px; margin-bottom:20px; "></div>

<div class="row">
        <div id="box-penilaian"></div>
</div>

<div class="row" id="table-dashboard">

        <table class="table table-hover" id="datatablesSimple">
            <thead>
                <tr>
                    <th scope="col" class="w-15">Nama Peserta</th>
                    <th scope="col" class="w-5">Jumlah Voter</th>
                    <th scope="col" class="w-5">Nilai Rata-rata</th>
                    <th scope="col" class="w-10">Aksi</th>
                </tr>
            </thead>
        @foreach($peserta as $pes)
            @php
                $jmlvote = \App\Models\Voter::where('peserta_id', $pes->id)->count();
                $jmlnilai = \App\Models\Voter::where('peserta_id', $pes->id)->sum('nilai');
                if($jmlvote == 0 ){
                    $nilai = 0;
                } else {
                    $nilai = number_format(($jmlnilai / $jmlvote), 2) ;
                }
            @endphp
            <tr>
                <td class="w-25">{{$pes->nama_peserta}}</td>
                <td class="w-5">{{$jmlvote}}</td>
                <td class="w-5">{{$nilai}}</td>
                <td class="w-10">
                    @if($pes->vote_status == "Y")
                        <form action="/peserta/vote-status" method="post" style="display:inline-block">
                            @csrf
                            <input type="hidden" name="vote_status" value="N">
                            <input type="hidden" name="id" value="{{$pes->id}}">
                            <input type="submit" class="btn btn-sm btn-success" value="Tutup Vote">
                        </form> 
                    @else
                        <form action="/peserta/vote-status" method="post" style="display:inline-block">
                            @csrf
                            <input type="hidden" name="vote_status" value="Y">
                            <input type="hidden" name="id" value="{{$pes->id}}">
                            <input type="submit" class="btn btn-sm btn-warning" value="Buka Vote" >
                        </form> 
                    @endif

                    @if($pes->vote_status == "T" )
                    <form action="/peserta/vote-status" method="post" style="display:inline-block">
                        @csrf
                        <input type="hidden" name="vote_status" value="N">
                        <input type="hidden" name="id" value="{{$pes->id}}">
                        <input type="submit" class="btn btn-sm btn-success" value="Persiapan Aktif">
                    </form> 
                    @elseif( $pes->vote_status == "H")
                    <form action="/peserta/vote-status" method="post" style="display:inline-block">
                        @csrf
                        <input type="hidden" name="vote_status" value="N">
                        <input type="hidden" name="id" value="{{$pes->id}}">
                        <input type="submit" class="btn btn-sm btn-danger" value="Tutup Vote">
                    </form> 
                    @else
                    <form action="/peserta/vote-status" method="post" style="display:inline-block">
                        @csrf
                        <input type="hidden" name="vote_status" value="T">
                        <input type="hidden" name="id" value="{{$pes->id}}">
                        <input type="submit" class="btn btn-sm btn-primary" value="Persiapan" >
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
</div>


<script>



    @if(count($dibuka) > 0)

        

        @if(session()->has('success'))

            $(document).ready(function() {
                // Menentukan waktu tujuan 1 menit dari sekarang
                var countDownDate = new Date().getTime() + (60 * 2000); // Tambahkan 60.000 milidetik (1 menit)

                // Memperbarui hitungan mundur setiap 1 detik
                var countdownFunction = setInterval(function() {
                    // Tanggal dan waktu sekarang
                    var now = new Date().getTime();

                    // Selisih antara tanggal dan waktu tujuan dengan tanggal dan waktu sekarang
                    var distance = countDownDate - now;

                    // Menghitung menit dan detik
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Menampilkan hasil pada elemen dengan id "countdown"
                    $("#countdown").html("<span style='border: 1px solid grey; padding:10px 25px 10px 25px; border-radius:10px;'>"+minutes + " menit, " + seconds + " detik </span>");

                    // Menghentikan hitungan mundur jika waktu tujuan telah tercapai
                    if (distance < 0) {
                    clearInterval(countdownFunction);
                    $("#countdown").html("Vote Ditutup!");
                        
                        $.ajax({
                            type: "POST",
                            url: "/peserta/vote-off",
                            data: {
                                _token : "{{ csrf_token() }}",
                                "id" : "{{ $dibuka[0]->id }}"
                            },
                            success: function (response) {
                                if (response.status == true) {
                                    location.reload();
                                } else {
                                    alert('Gagal menutup vote');
                                }
                            }
                        });
                    }
                }, 1000);

                setInterval(function() {
                    getBoxPenilaian();
                }, 1000);

                $("#table-dashboard").attr("style", "display:none;");
                $("#footer").attr("style", "display:none;");
                $(".navbar-brand").attr("style", "display:none;");
                $(".navbar-nav").attr("style", "display:none;");
                $("#navbar-layout").attr("style", "display:none;");

            });

            function getBoxPenilaian()
            {
                $.ajax({
                    type: "POST",
                    url: "/voter/box-penilaian",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#box-penilaian').html(`
                                <div class="col-xl-4 col-md-6" style="margin:0 auto;">
                                    <div class="card bg-primary text-white mb-4 text-center">
                                        <div class="card-body">
                                            <h1>${response.data.peserta[0].nama_peserta}</h3>
                                            <br>
                                            <h3>Voters : ${response.data.jml_vote}</h3><br>
                                            <h1>${response.data.peserta[0].kode}</h1>
                                        </div>
                                        <div class="card-footer">
                                            <h1 class="text-center">Nilai : ${response.data.nilai}</h1>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                    }
                });
            }
        @else
            $(document).ready(function () {
                $.ajax({
                    type: "POST",
                    url: "/peserta/vote-off",
                    data: {
                        _token : "{{ csrf_token() }}",
                        "id" : "{{ $dibuka[0]->id }}"
                    },
                    success: function (response) {
                        if (response.status == true) {
                            location.reload();
                        } else {
                            alert('Gagal menutup vote');
                        }
                    }
                });
            });
        @endif

    @endif
</script>
@endsection
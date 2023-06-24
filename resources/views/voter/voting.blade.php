<!DOCTYPE html>
<html>
<head>
    <title>Voting</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .fullscreen-image {
            height: 100vh;
            background-size: cover;
            background-position: center;
        }

        @font-face {
            font-family: 'Air Strike';
            src: url('/font/airstrike.ttf') format('truetype'); /* Ganti dengan nama font dan path file font yang sesuai */
            /* Tambahan format-font lainnya jika diperlukan */
        }

        /* Contoh penggunaan font pada elemen */
        h1 {
            font-family: 'Air Strike', sans-serif; /* Gunakan nama font yang telah ditentukan */
            font-size:100px;
            color:;
            margin:0;
            padding:0;
            line-height:80px;
        }

        #vote-code{
            font-family: 'Share Tech Mono', monospace;
            font-weight: bold;
        }

        .foto {
            display: flex;
            justify-content: center; /* Mengatur horisontal ke tengah */
            align-items: center; /* Mengatur vertikal ke tengah */

        }
        .dalemfoto{
            box-shadow: -3px 0px 5px 20px rgba(193,24,0,0.6);
            -webkit-box-shadow: -3px 0px 5px 20px rgba(193,24,0,0.6);
            -moz-box-shadow: -3px 0px 5px 20px rgba(193,24,0,0.6);
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="fullscreen-image" style="background-image: url('/background.png');">

                    <div class="row" id="full-body">
                        <div class="col-5" id="divsatu">
                            <br><br><br>

                            <div class="text-center logo-voice mt-5" >
                                <img src="/logo-the-voice-web.png" alt="" style="width:650px;">
                            </div>
                            <div class="perhitungan" style="display:block;">
                                <br><br><br><br>
                                <h1 id="hitungan-mundur" class="text-center" style="color:white; font-size:120px;">00 : 00</h1><br><br>
                                <h1 id="font-jumlah" class="text-center" style="color:white; font-size:40px;">Jumlah Voters</h1>
                                <h1 id="jumlah-voters" class="text-center" style="color:white; font-size:120px;">0 </h1><br><br>
                                <h1 id="font-nilai" class="text-center" style="color:white; font-size:40px;">Nilai </h1>
                                <h1 id="nilai" class="text-center" style="color:white; font-size:120px;">0 </h1><br><br>
                            </div>
                            <div class="logo-voice-engineering">
                                <img src="/logo-the-voice-web.png" alt="" srcset="" style="margin:0 auto; width:600px; display:none; margin-top:100px;">
                            </div>
                            <h1 class="text-center" style="color:white; font-size: 40px; letter-spacing:20px;">VOTE KODE</h1>
                            <div style="background-color:white; width:400px; padding:30px; margin:0 auto; border-radius:20px;">
                                <h1 id="vote-code" class="text-center" style="color:#340100; font-size:150px;">XXX </h1>
                            </div>
                        </div>
                        <div class="col-6" id="divdua">
                            <br><br><br>
                            <div class="foto-container" style="width:1000px; margin:0 auto;">
                                <h1 class="text-center" style="color:white;font-size:120px;">The Voice</h1>
                                <h1 class="text-center" style="color:white;font-size:120px;">Of Engineering</h1>
                                <div class="foto mt-5">
                                    <img id="foto_peserta" class="dalemfoto" src="/upload/peserta/telegram.jpg" alt="" style="magin:0 auto; width:600px; border-radius:0px 250px 0px 250px;"><br>
                                    
                                </div>
                                <div class="nama" style="margin:0 auto; border:1 px solid white;">
                                    <h1 id="nama_peserta" style="color:#ffe400; font-size:60px; display:flex;justify-content:right; ">Dela Fitriyani Handayani </h1>
                                    <h2 id="kategori" style="color:white; font-size:40px; display:flex;justify-content:right; ">MAHASISWA KESEHATAN</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content: center; margin-top:80px;">
                        <img src="/logo-the-voice-web.png" alt="" srcset="" id="full-logo" >
                    </div>
                </div> 
               
            </div>
            
        </div>
    </div>
    <script>
        $(document).ready(function () {

            setInterval(() => {
                hitung_voting();
            }, 1000);
            
            let maxAttempts = 10; // Jumlah maksimum percobaan
            let interval = 10000; 
            let attemptCount = 0; 

            function hitung_voting() {
                $.ajax({
                    type: "POST",
                    url: "/voting/peserta",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status == true) {
                            if (response.data.peserta[0].vote_status == "T") {

                                $("#full-logo").attr("style", "display:none");
                                $("#divsatu").attr("style", "display:block");
                                $("#divdua").attr("style", "display:block");
                                $(".perhitungan").attr("style", "display:none");
                                $(".logo-voice").attr("style", "display:block");
                                $("#vote-code").text('XXX');
                                $("#nama_peserta").text(response.data.peserta[0].nama_peserta);
                                $("#kategori").text(response.data.peserta[0].kategori);
                                if (response.data.peserta[0].foto) {
                                    $("#foto_peserta").attr("src","/storage/upload/peserta/"+response.data.peserta[0].foto);
                                }else {
                                    $("#foto_peserta").attr("src","/upload/peserta/telegram.jpg");
                                }
                            } else if(response.data.peserta[0].vote_status == "H"){

                                $("#full-logo").attr("style", "display:none");
                                $("#divsatu").attr("style", "display:block");
                                $("#divdua").attr("style", "display:block");
                                $(".perhitungan").attr("style", "display:block");
                                $(".logo-voice").attr("style", "display:none");
                                $("#hitungan-mundur").text("00 : 00");
                                $("#jumlah-voters").text(response.data.jml_vote);
                                $("#nilai").text(response.data.nilai);
                                $("#vote-code").text(response.data.peserta[0].kode);
                                $("#nama_peserta").text(response.data.peserta[0].nama_peserta);
                                $("#kategori").text(response.data.peserta[0].kategori);
                                $("#status_vote").val('vote_dibuka');
                                if (response.data.peserta[0].foto) {
                                    $("#foto_peserta").attr("src","/storage/upload/peserta/"+response.data.peserta[0].foto);
                                }else {
                                    $("#foto_peserta").attr("src","/upload/peserta/telegram.jpg");
                                }
                            }else{

                                $("#full-logo").attr("style", "display:none");
                                $("#divsatu").attr("style", "display:block");
                                $("#divdua").attr("style", "display:block");
                                $(".perhitungan").attr("style", "display:block");
                                $(".logo-voice").attr("style", "display:none");
                                // let hitungan_mundur = $("#hitungan-mundur").text().split(" ")[0];
                                // let text_hitungan_mundur = "";
                                // if (hitungan_mundur <= 0 ) {
                                //     text_hitungan_mundur = "Vote Ditutup";                                  
                                // } else {
                                //     text_hitungan_mundur = (hitungan_mundur - 1)+" detik";   
                                // }
                                // $("#hitungan-mundur").text(text_hitungan_mundur);
                                $("#jumlah-voters").text(response.data.jml_vote);
                                $("#nilai").text(response.data.nilai);
                                $("#vote-code").text(response.data.peserta[0].kode);
                                $("#nama_peserta").text(response.data.peserta[0].nama_peserta);
                                $("#kategori").text(response.data.peserta[0].kategori);
                                $("#status_vote").val('vote_dibuka');
                                if (response.data.peserta[0].foto) {
                                    $("#foto_peserta").attr("src","/storage/upload/peserta/"+response.data.peserta[0].foto);
                                }else {
                                    $("#foto_peserta").attr("src","/upload/peserta/telegram.jpg");
                                }

                                let targetDate = new Date(response.data.peserta[0].countdown).getTime();
                                let now = new Date().getTime();
                                let hitungan_mundur = targetDate - now;
                                let minutes = Math.floor((hitungan_mundur % (1000 * 60 * 60)) / (1000 * 60));
                                let seconds = Math.floor((hitungan_mundur % (1000 * 60)) / 1000);

                                if (seconds <= 00 && minutes <= 00 ) {
                                    $("#hitungan-mundur").text("Vote Ditutup");
                                } else {
                                    $("#hitungan-mundur").text(minutes + " : "+seconds);
                                }

                            }
                            
                        } else {
                            $("#full-logo").attr("style", "display:block");
                            $("#divsatu").attr("style", "display:none");
                            $("#divdua").attr("style", "display:none");
                        }

                    },
                    error: function(xhr, status, error) {
                        attemptCount++;
                        if (attemptCount < maxAttempts) {
                            // Jika jumlah percobaan belum mencapai batas maksimum
                            setTimeout(hitung_voting, interval);
                        } else {
                            let confirmation = confirm('Periksa koneksi internet anda!, apakah anda akan merefresh halaman?');
                            if (confirmation) {
                                location.reload();
                            }
                        }
                    }
                });
            }
        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
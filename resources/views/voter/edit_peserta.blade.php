@extends('layout.content')
 
@section('title', 'Ubah Peserta')

@section('judul')
<h1 class="mt-4">Ubah Peserta</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Ubah Peserta</li>
                        </ol>
@endsection
 
@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="/peserta/{{$peserta->id}}/update" method="post">
            @csrf
        <div class="form-group mt-3">
            <label for="nama_peserta">Nama Peserta</label>
            <input type="text" class="form-control" name="nama_peserta" id="nama_peserta" placeholder="Masukan Nama Peserta" value="{{ old('nama_peserta', $peserta->nama_peserta) }}" autofocus>
        </div>
        <div class="form-group mt-3">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin1" value="Laki-laki" @if($peserta->jenis_kelamin == 'Laki-laki') checked @endif >
                <label class="form-check-label" for="jenis_kelamin1" >
                    Laki-laki
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin1" value="Perempuan" @if($peserta->jenis_kelamin == 'Perempuan') checked @endif>
                <label class="form-check-label" for="jenis_kelamin1">
                    Perempuan
                </label>
            </div>
        </div>
        <div class="form-group mt-3">
            <label for="tempat_lahir">Tempat Lahir</label>
            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}">
        </div>
        <div class="form-group mt-3">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" class="form-control" id="start" name="tanggal_lahir"
            value="{{old('tanggal_lahir',$peserta->tanggal_lahir)}}">
        </div>
        <div class="form-group mt-3">
            <label for="alamat">Alamat</label>
            <textarea id="alamat" class="form-control" name="alamat" rows="3" placeholder="Masukan alamat">{{ old('alamat', $peserta->alamat)}}</textarea>
        </div>
        <div class="form-group mt-3">
            <label for="kode">Kode</label>
            <input type="text" class="form-control" name="kode" id="kode" placeholder="Masukan kode" value="{{ old('kode', $peserta->kode)}}">
        </div>
        <br>
        <input type="submit" class="btn btn-voice" value="Submit">
        <a class="btn btn-success" onclick="generate()">generate code</a>
        </form>
    </div>

</div>

<script>

    function generate(){
        $("#kode").val(makeid(3));
    }

    function makeid(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
        }
        return result;
    }

</script>
@endsection
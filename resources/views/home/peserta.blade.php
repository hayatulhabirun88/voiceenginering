@extends('layout.content')
 
@section('title', 'Peserta')

@section('judul')
<h1 class="mt-4">Peserta</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Peserta</li>
                        </ol>
@endsection
 
@section('content')

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Peserta <a href="/peserta/create" class="btn btn-sm btn-primary" style="float:right">Tambah Peserta</a>
        <a href="/export/report-data-peserta" class="btn btn-sm btn-success " style="float:right;margin-right:5px;">Data Peserta</a>
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>No</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Kelamin</th>
                    <th>Fakultas</th>
                    <th>Kategori</th>
                    <th>Kode</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Aksi</th>
                    <th>No</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Kelamin</th>
                    <th>Fakultas</th>
                    <th>Kategori</th>
                </tr>
            </tfoot>
            <tbody>
                @php $no=1; @endphp
                @foreach($peserta as $p)
                <tr>
                    <td>
                        <form action="/peserta/{{$p->id}}/delete" method="post" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="return confirm('apakah anda yakin akan menghapus peserta ini?')"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            <!-- <input type="submit" class="btn btn-sm btn-danger" value="hapus"  /> -->
                        </form>
                        <a href="/peserta/{{$p->id}}/edit" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
                    </td>
                    <td>{{ $no++}}</td>
                    <td>{{$p->nama_peserta}}</td>
                    <td>{{$p->jenis_kelamin}}</td>
                    <td>{{$p->fakultas}}</td>
                    <td>{{$p->kategori}}</td>
                    <th>{{$p->kode}}</th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
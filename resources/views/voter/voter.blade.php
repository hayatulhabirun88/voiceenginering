@extends('layout.content')
 
@section('title', 'Voter')

@section('judul')
<h1 class="mt-4">Voter</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Voter</li>
                        </ol>
@endsection
 
@section('content')

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i> Voter

        <form action="/voter/truncate" method="post" style="display:inline">
            @csrf
            <button class="btn btn-sm btn-danger" style="float:right; margin-right:5px;" onclick="return confirm('Apakah anda yakin akan MERESET/MENGOSONGKAN data Voters?')">Reset Vote</button>
        </form>
        <a href="/export/voter" class="btn btn-sm btn-success" style="float:right; margin-right:5px;" >Data Keseluruhan</a>
        <a href="/export/voter-nilai" class="btn btn-sm btn-primary" style="float:right; margin-right:5px;" >Data Nilai Peserta</a>
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Voter</th>
                    <th>Id Telegram</th>
                    <th>Menilai</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Nama Voter</th>
                    <th>Id Telegram</th>
                    <th>Menilai</th>
                    <th>Nilai</th>
                </tr>
            </tfoot>
            <tbody>
                @php $no=1; @endphp
                @foreach($voter as $vot)
                <tr>
                    <td>{{ $no++}}</td>
                    <td>{{$vot->nama_voter}}</td>
                    <td>{{$vot->telegram_id}}</td>
                    <td>{{$vot->nama_peserta}}</td>
                    <th>{{$vot->nilai}}</th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
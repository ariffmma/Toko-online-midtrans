<div class="container">
    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <td>No.</td>
                            <td>Tanggal Pesan</td>
                            <td>Nama Produk</td>
                            <td>Status</td>
                            <td>Total Harga</td>
                            <td>Aksi</td>
                            <td>Pengiriman</td>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1 ?>
                        @forelse ($belanja as $pesanan)
                        <tr>
                            <td>{{  $no++ }}</td>
                            <td>{{ $pesanan->created_at }}</td>
                            <td>
                                <?php $produk = \App\Models\Produk::where('id', $pesanan->produk_id)->first(); ?>
                                <img src="{{ asset('storage/photos/',$produk->gambar) }}" width="62px" alt="">{{ $produk->nama }}

                            </td>
                            <td>
                                @if ($pesanan->status == 0)
                                <strong>Pesanan belum ditambahkan ongkir</strong>                                   
                                @endif
                                @if ($pesanan->status == 1)
                                <strong>Pesanan sudah ditambahkan ongkir</strong>
                                @endif
                                @if ($pesanan->status == 2)
                                <strong>Pesanan telah dipilih pembayarannya</strong>
                                @endif
                            </td>
                            <td><strong>Rp. {{ number_format($pesanan->total_harga) }}</strong></td>
                            <td>
                                @if ($pesanan->status == 0)
                                    <a href="{{ url('TambahOngkir/'.$pesanan->id) }}" class="btn btn-warning btn-block">Tambah Ongkir</a>
                                @endif
                                @if ($pesanan->status == 1)
                                    <a href="{{ url('Bayar/'.$pesanan->id) }}" class="btn btn-primary btn-block">Pilih Pembayaran</a>
                                @endif
                                @if ($pesanan->status == 2)
                                <a href="{{ url('Bayar/'.$pesanan->id) }}" class="btn btn-primary btn-block">Lihat Status</a>
                            @endif
                            </td>
                            <td>
                                @if ($pesanan->jenis_service_pengiriman == 'jne')
                                <strong>JNE</strong> 
                                @endif
                                @if ($pesanan->jenis_service_pengiriman == 'pos')
                                <strong>Pos</strong> 
                                @endif
                                @if ($pesanan->jenis_service_pengiriman == 'tiki')
                                <strong>Tiki</strong> 
                                @endif
                                @if ($pesanan->jenis_service_pengiriman == null)
                                <strong>Anda belum melakukan pembayaran</strong> 
                                @endif
                            </td>
                           
                        </tr>
                            
                        @empty
                            <tr>
                                <td colspan="7">
                                    Data Kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>
    
</div>

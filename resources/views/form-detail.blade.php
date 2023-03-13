<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>{{ $produk->nama_produk }}</title>
    <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        body {
            background: #eee
        }

        .ratings i {
            font-size: 16px;
            color: red
        }

        .strike-text {
            color: red;
            text-decoration: line-through
        }

        .product-image {
            width: 100%
        }

        .dot {
            height: 7px;
            width: 7px;
            margin-left: 6px;
            margin-right: 6px;
            margin-top: 3px;
            background-color: blue;
            border-radius: 50%;
            display: inline-block
        }

        .spec-1 {
            color: #938787;
            font-size: 15px
        }

        h5 {
            font-weight: 400
        }

        .para {
            font-size: 16px
        }

        {{-- nama, nomor whatsapp, email, alamat --}}
    </style>
</head>

<body className='snippet-body'>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-10">
                <div class="row p-2 pt-5 bg-white border rounded m-1">
                    <div class="col-md-5 mt-1"><img class="img-fluid img-responsive rounded product-image" src="/{{ $prod->gambar ?? 'default-image.png' }}"></div>
                    <div class="col-md-7 mt-1">
                        <h5>{{ $produk->nama_produk }}</h5>
                        <div class="d-flex flex-row">
                            {{-- Harga produk --}}
                            <p class="text-danger mr-1"><strong>Rp. {{ $produk->harga }}</strong></p>
                            
                            {{-- <div class="ratings mr-2">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div><span>310</span> --}}
                        </div>
                        <p class="text-justify text-truncate para mb-0">{!! $produk->content_html !!}<br><br></p>
                    </div>
                </div>

                <form action="{{ url('registrasi') }}" method="post" enctype="multipart/form-data">
                    <div class="row p-2 pt-5 bg-white border rounded m-1">
                        @csrf
                        <div class="col-md-12 mb-3 ">
                            <center><h4>Form Pembelian</h4></center>
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>	
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12 mb-3">
                            <input type="hidden" value="{{ $produk->id }}" name="id_produk" class="form-control">
                            <label for="">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Nomor Whatsapp</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="">Alamat</label>
                            <textarea name="alamat" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" id="" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <button type="submit" class="btn btn-success">Beli Produk</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js'></script>
    <script type='text/javascript' src='#'></script>
    <script type='text/javascript' src='#'></script>
    <script type='text/javascript' src='#'></script>
    <script type='text/javascript'>
        #
    </script>
    <script type='text/javascript'>
        var myLink = document.querySelector('a[href="#"]');
        myLink.addEventListener('click', function(e) {
            e.preventDefault();
        });
    </script>

</body>

</html>
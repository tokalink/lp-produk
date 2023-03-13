<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>{{ $kategori->nama_kategori ?? 'Jasa Layanan'}}</title>
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

        ul {
            line-height: 1.5em;
            margin: 5px 0 15px;
            padding: 0;
        }

        li {
            list-style: none;
            position: relative;
            padding: 0 0 10px 20px;
        }   

        li.star::before {
            content: ""; 
            position: absolute; 
            left: 0; 
            top: 5px;
            background: #117a8b;
            width: 10px; 
            height: 10px;
            text-align: center; 
            transform: rotate(0deg);
        }

        li.star::after {
            content: ""; 
            position: absolute; 
            top: 5px; 
            left: 0; 
            height: 10px; 
            width: 10px; 
            background: #117a8b;
            transform: rotate(45deg);
        }
    </style>
</head>

<body className='snippet-body'>
    <div class="container mt-5 mb-5" style="max-width: 1450px;">
        <div class="d-flex justify-content-center row">
            <div class="col-lg-3 mr-4 col-12">
                <h4 class="ml-4 mb-3">Daftar Kategori</h4>
                <div class="row bg-white border rounded" style="margin: 0px 2px 45px 19px; padding: 10px 10px 0 25px;" >
                    <ul>
                        @foreach ($kategoris as $item)
                        <li class="star">
                            <a href="#title" onclick="tampilKategori('{{ $item->slug }}')" rel="noopener noreferrer">
                                {{ $item->nama_kategori }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                <div class="row">
                    <div class="col-12">
                        <h4 id="title">Daftar Produk</h4>
                    </div>
                    <div class="col-12">
                        <div class="row" id="list-produk">
                            @foreach($produk as $prod)
                                <div class="col-md-6">
                                    <div class="row bg-white border rounded m-2" style="padding: 20px 10px 20px 10px;">
                                        <div class="col-md-4 mt-1"><img class="img-fluid img-responsive rounded product-image" src="{{ $prod->gambar ?? url('/default-image.png') }}"></div>
                                        <div class="col-md-8 mt-1">
                                            <h5>{{ $prod->nama_produk }}</h5>
                                            <div class="d-flex flex-row align-items-center">
                                                <h4 class="mr-1">Rp. {{ $prod->harga }}</h4>
                                            </div>
                                            <p class="text-justify text-truncate para mb-0">{{ $prod->detail_produk ?? '' }}<br><br></p>
                                            <div class="d-flex flex-column mt-4">
                                                <a href="{{ url('form-detail?id='.$prod->id) }}" target="_blank" class="btn btn-primary btn-sm" type="button">Pesan Sekarang</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js'></script>
    <script type='text/javascript'>
        // var myLink = document.querySelector('a[href="#"]');
        // myLink.addEventListener('click', function(e) {
        //     e.preventDefault();
        // });

        function tampilKategori(kategori){
            let url = location.origin + '/' + kategori
            $.ajax({
                url: url,
                success: function(res) {
                    $('#list-produk').empty()
                    $('#title').html(res.kategori.nama_kategori)
                    let html;
                    $.each(res.produk, function (index, value) {
                        html = `
                        <div class="col-md-6">
                            <div class="row bg-white border rounded m-2" style="padding: 20px 10px 20px 10px">
                                <div class="col-md-4 mt-1"><img class="img-fluid img-responsive rounded product-image" src="${ value.gambar ?? (location.origin+'/default-image.png') }"></div>
                                <div class="col-md-8 mt-1">
                                    <h5>${ value.nama_produk }</h5>
                                    <div class="d-flex flex-row align-items-center">
                                        <h4 class="mr-1">Rp. ${ value.harga }</h4>
                                    </div>
                                    <p class="text-justify text-truncate para mb-0">${ value.detail_produk ?? '' }<br><br></p>
                                    <div class="d-flex flex-column mt-4">
                                        <a href="${location.origin +'/form-detail?id='+ value.id}" target="_blank" class="btn btn-primary btn-sm" type="button">Pesan Sekarang</a>
                                    </div>
                                </div>
                            </div>
                        </div>`
                        $('#list-produk').append(html)
                    })
                }
            })
        }
    </script>

</body>

</html>
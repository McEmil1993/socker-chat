<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script> -->
    <!-- croppie  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <style>
        .jumping-dots-loader span {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 100%;
            background-color: #424245 ;
            margin: 20 5px
        }

        .jumping-dots-loader span:nth-child(1) {
            animation: bounce 1s ease-in-out infinite
        }

        .jumping-dots-loader span:nth-child(2) {
            animation: bounce 1s ease-in-out 0.33s infinite
        }

        .jumping-dots-loader span:nth-child(3) {
            animation: bounce 1s ease-in-out 0.66s infinite
        }

        @keyframes bounce {

            0%,
            75%,
            100% {
                -webkit-transform: translateY(0);
                -ms-transform: translateY(0);
                -o-transform: translateY(0);
                transform: translateY(0)
            }

            25% {
                -webkit-transform: translateY(-7px);
                -ms-transform: translateY(-7px);
                -o-transform: translateY(-7px);
                transform: translateY(-7px)
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#" data-toggle="modal" data-target="#modal-default">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item ">
                             
                                  <img class="direct-chat-img" src="{{ Auth::user()->image_name != '' ? 'dist/img/'.Auth::user()->image_name: asset('dist/img/AdminLTELogo.png');  }}" alt="Message User Image">
                      
                            </li>
                            
                           
                            <li class="nav-item ">  
                                <a class="nav-link" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Upload Image</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <div>
                                <img id="previewImage" src="{{ asset('dist/img/default-150x150.png') }}" alt="Preview" style="max-width: 300px;">
                            </div>
                        </center>
                        <input type="file" id="imageInput">
                    </div>
                    <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="cropButton" data-dismiss="modal" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
                <!-- /.modal-dialog -->
            </div>
    </div>
    <script>
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    const cropButton = document.getElementById('cropButton');
    let cropper;

    imageInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      const reader = new FileReader();

      reader.onload = () => {
        if (previewImage) {
          previewImage.src = reader.result;
          cropper = new Cropper(previewImage, {
            aspectRatio: 1,
            viewMode: 1,
            crop: function(event) {
    
            },
          });
        }
      };

      reader.readAsDataURL(file);
    });

    cropButton.addEventListener('click', () => {

      const croppedCanvas = cropper.getCroppedCanvas();
      const croppedImage = croppedCanvas.toDataURL('image/jpeg');
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/save-cropped-image', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ image: croppedImage }),
        })
        .then(response => response.json())
        .then(data => {
            setTimeout(() => {
                location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Error saving image:', error);
        });
    });
  </script>
</body>
</html>

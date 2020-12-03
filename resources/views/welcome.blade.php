<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <script src="{{ asset('js/app.js') }}" defer></script>

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

  <!-- Styles -->
  <style>
  html body {
    background-color: #fff;
    color: #636b6f;
    font-family: 'Nunito', sans-serif;
    font-weight: 200;
    height: 100vh;
    margin: 0;
  }

  .full-height {
    height: 100vh;
  }

  .flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
  }

  .position-ref {
    position: relative;
  }

  .content {
    text-align: center;
  }
  </style>
</head>

<body>
  <div class="flex-center position-ref full-height">

    <div class="content">
      <div class="card">
        <h5 class="card-header">ADD BACKGROUND AND MODEL FILE TO GET STARTED</h5>
        <div class="card-body">
          <form action="/model" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="model" />
            <input type="file" name="background" />
            <input class="btn btn-primary" type="submit" value="Upload with Model Viewer" />
            <input class="btn btn-primary" type="submit" value="Upload with threejs" formaction="/threejs" />
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <script src="{{ asset('js/app.js') }}" defer></script>

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
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
            <div class="flex-col">
              <label for="loadModel">Load model file</label>
              <input type="file" name="model" id="loadModel" />

              <label for="loadBkgd">Load background file</label>
              <input type="file" id="loadBkgd" name="bkgd" />
            </div>
            <div class="flex-col">
              <input class="btn btn-primary" type="submit" value="Upload with Model Viewer" />
              <input class="btn btn-primary" type="submit" value="Upload with threejs" formaction="/threejs" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
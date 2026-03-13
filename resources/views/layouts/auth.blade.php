<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','Pipeline Prospek') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
  {{ $slot }}

  <script src="/js/api.js"></script>
</body>
</html>

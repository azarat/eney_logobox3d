<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token()  }}">
    <style type="text/css">
        @font-face {
            font-family: 'Helvetica Neue';
            src: url('/fonts/HelveticaNeueCyr-Roman.woff');
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <link href="/dist/css/chunk-vendors.css" rel="stylesheet">
    <link href="/dist/css/app.css" rel="stylesheet">
    <script type="text/javascript">
      function findGetParameter(parameterName) {
        let result = null;
        let tmp = [];
        window.location.search
          .substr(1)
          .split('&')
          .forEach((item) => {
            tmp = item.split('=');
            if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
          });
        return result;
      }
      const discount = findGetParameter('discount');

    window.printFrame = {
        lang: "{{ $lang }}",
        productModel: "{{ $productModel }}",
        sessionId: "{{ $sessionId }}",
        vizModelId: "{{ $viz_model_id }}",
        siteId: "{{ $siteId }}",
        discount: discount
    }
    </script>
</head>
<body>
    <div id="app">

    </div>
    <script src="/dist/js/chunk-vendors.js"></script>
    <script src="/dist/js/app.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 Page Not Found</title>
  <style>
    /* Basic styles for the page */
    body {
      background-color: #f5f5f5;
      font-family: Poppins, sans-serif;
      font-size: 16px;
      color: #333;
      margin: 0;
      padding: 0;
    }

    /* Styles for the 404 text */
    .error-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }
    .error-text h1 {
      font-size: 72px;
      font-weight: bold;
      margin: 0 0 20px;
    }
    .error-text p {
      font-size: 24px;
      margin: 0;
    }

    /* Styles for the image */
    .error-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('https://images.pexels.com/photos/374918/pexels-photo-374918.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
      background-size: cover;
      background-position: center;
      opacity: 0.4;
    }

    /* Styles for the back button */
    .back-button {
      display: inline-block;
      background-color: #333;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      text-decoration: none;
      padding: 10px 20px;
      margin-top: 20px;
      border-radius: 4px;
    }
    .back-button:hover {
      background-color: #444;
    }
  </style>
</head>
<body>
  <div class="error-image"></div>
  <div class="error-text">
    <h1>404</h1>
    <p>Page not found</p>
    <a href="./" class="back-button">Go back to homepage</a>
  </div>
</body>
</html>

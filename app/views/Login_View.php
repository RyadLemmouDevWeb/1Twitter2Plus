<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Signup</title>
  <link rel="stylesheet" href="/css/output.css">
</head>
<body class="bg-gradient-to-r from-slate-300 to-slate-700 flex justify-center items-center h-screen m-0 overflow-hidden">
  <div class="gradients-container absolute inset-0 filter blur-2xl">
    <div class="interactive absolute w-full h-full bg-gradient-to-r from-indigo-500 to-transparent rounded-full mix-blend-hard-light"></div>
  </div>
  <div class="flex justify-center w-full space-x-4 z-10">
    <div class="bg-white p-6 shadow-md rounded-lg w-80 login-form">
      <div class="skeleton">
        <h2 class="text-center text-xl mb-4 animate-pulse bg-gray-200 h-6 w-24 mx-auto rounded"></h2>
        <div class="mb-3">
          <div class="animate-pulse bg-gray-200 h-10 rounded-md mb-3"></div>
          <div class="animate-pulse bg-gray-200 h-10 rounded-md mb-3"></div>
        </div>
        <div class="animate-pulse bg-gray-200 h-10 rounded-md mb-3"></div>
        <p class="text-center text-sm mt-4 animate-pulse bg-gray-200 h-4 w-48 mx-auto rounded"></p>
      </div>
      <div class="form-content hidden">
        <h2 class="text-center text-xl mb-4">Login</h2>
        <form method="POST">
          <input type="hidden" name="action" value="login">
          <div class="mb-3">
            <input type="email" name="email" required placeholder="Email" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-3">
            <input type="password" name="password" required placeholder="Password" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <button type="submit" name='login' class="w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Login</button>
          <p class="text-center text-sm mt-4">No account? <a href="javascript:void(0);" onclick="showSignup()" class="text-blue-500 hover:underline">Sign up here</a></p>
        </form>
        <div class="mb-3 text-center text-red-500">
          <?php if (isset($_GET['error'])) { echo $_GET['error']; } ?>
        </div>
      </div>
    </div>
    <div class="bg-white p-6 shadow-md rounded-lg w-80 signup-form hidden">
      <div class="skeleton">
        <h2 class="text-center text-xl mb-4 animate-pulse bg-gray-200 h-6 w-32 mx-auto rounded"></h2>
        <div class="mb-3 flex space-x-2">
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
        </div>
        <div class="animate-pulse bg-gray-200 h-10 rounded-md mb-3"></div>
        <div class="mb-3 flex space-x-2">
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
        </div>
        <div class="mb-3 flex space-x-2">
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
        </div>
        <div class="mb-3 flex space-x-2">
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
          <div class="animate-pulse bg-gray-200 h-10 rounded-md w-1/2"></div>
        </div>
        <div class="mb-3 flex flex-col space-y-2">
          <div class="animate-pulse bg-gray-200 h-10 rounded-md"></div>
          <div class="animate-pulse bg-gray-200 h-10 rounded-md"></div>
        </div>
        <div class="animate-pulse bg-gray-200 h-10 rounded-md mb-3"></div>
        <p class="text-center text-sm mt-4 animate-pulse bg-gray-200 h-4 w-48 mx-auto rounded"></p>
      </div>
      <div class="form-content hidden">
        <h2 class="text-center text-xl mb-4">Sign Up</h2>
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="register">
          <div class="mb-3 text-center text-red-500">
            <?php if (isset($_GET['error'])) { echo $_GET['error']; } ?>
          </div>
          <div class="mb-3 text-center text-blue-500">
            <?php if (isset($_GET['message'])) { echo $_GET['message']; } ?>
          </div>
          <div class="mb-3 flex space-x-2">
            <input type="text" name="firstname" required placeholder="First Name" class="w-full p-2 border border-gray-300 rounded-md">
            <input type="text" name="lastname" required placeholder="Last Name" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <input type="text" name="display_name" required placeholder="Display Name" class="w-full p-2 border border-gray-300 rounded-md">
          <div class="mb-3 flex space-x-2">
            <input type="text" name="username" required placeholder="Username" class="w-full p-2 border border-gray-300 rounded-md">
            <input type="email" name="email" required placeholder="Email" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-3 flex space-x-2">
            <input type="password" name="password" required placeholder="Password" class="w-full p-2 border border-gray-300 rounded-md">
            <input type="date" name="birthdate" required class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-3 flex space-x-2">
            <input type="text" name="phone" placeholder="Phone" class="w-full p-2 border border-gray-300 rounded-md">
            <select name="genre" class="w-full p-2 border border-gray-300 rounded-md">
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3 flex flex-col space-y-2">
            <label for="picture" class="block text-sm">Profile Picture:</label>
            <input type="file" name="picture" id="picture" class="w-full p-2 border border-gray-300 rounded-md">
            <label for="header" class="block text-sm">Header Picture:</label>
            <input type="file" name="header" id="header" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Sign Up</button>
          <p class="text-center text-sm mt-4">Already have an account? <a href="javascript:void(0);" onclick="showLogin()" class="text-blue-500 hover:underline">Login here</a></p>
        </form>
      </div>
    </div>
  </div>
  <script src="/lib/login.js"></script>
</body>
</html>
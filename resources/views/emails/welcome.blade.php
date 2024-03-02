
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/core@6.1.10/main.min.css" />
  <!-- FullCalendar DayGrid plugin CSS -->
  <link rel="stylesheet" href="https://unpkg.com/@fullcalendar/daygrid@6.1.10/main.min.css" />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
  <title>Mihanz Catering</title>
</head>

<body>
    
    <main class="w-100  d-flex align-items-center mb-5">
      <div class="w-100 d-flex justify-content-center"><img src="mihanzLogo.png" alt=""></div>
        <div class="d-flex w-75 justify-content-start">
          
          <!-- <div class="display-4 fw-semibold mb-5 ">Hello, userName</div> -->
        </div>
        <div class="d-flex flex-column align-items-center mb-5">
            <!-- <div class="display-6 mb-5 w-75">Thank you for Registering to <b>Mihanz Catering</b> Enjoy browsing our services</div>
            
        </div>
        <div class="d-flex justify-content-center"><a href="../Index.html" class="btn btn-lg btn-primary m-5">Continue</a></div> -->
        <div class="w-50 mb-5">
          Dear <b class="fw-semibold">{{ $user->first_name }}</b>
        </div>
        <div class="w-50 mb-5" style="text-align: justify;">
          Welcome to <b>Mihanz Catering!</b> We are delighted to have you join our platform and become a part of our community. <br>
        </div>
        <div class="w-50  d-flex mb-5 " style="text-align: justify;">
          As a new member, you now have access to our intuitive and efficient catering reservation system designed to streamline the process of planning events and managing catering orders. Whether you're organizing a corporate luncheon, a wedding reception, or any other special occasion, we're here to simplify the catering experience for you.
        </div>
        
        
        <div class="w-50 mb-3" style="text-align: justify;">
          <b>Here's what you can do to get started:</b>
        </div>

        
        <div class="w-50 mb-5" style="text-align: justify;">
          <ul>
            <li class="mb-2"><b>Explore Our Features:</b> Log in to your account and take a tour of our platform. Familiarize yourself with our reservation system and menu options.</li>
            <li class="mb-2"><b> Create Your First Reservation:</b> Have an upcoming event in mind? Start by creating your first reservation. Our user-friendly interface makes it easy to specify your requirements and preferences.</li>
            <li class="mb-2"><b>Browse Menus :</b> Discover a wide range of menus and suppliers available on our platform. Whether you're craving international cuisine, traditional favorites, or dietary-specific options, you'll find something to suit your needs.</li>
            <li class="mb-2"><b>Manage Your Reservation:</b> Keep track of your reservations, review order details, and make any necessary adjustments.</li>
            <!-- <li><b>Reach Out for Support:</b> Need assistance or have questions? Our dedicated support team is here to help. Feel free to contact us at [Support Email] if you require any assistance or guidance.</li> -->
          </ul>
        </div>
        
        <div class="w-50 mb-5" style="text-align: justify;">
          We're committed to providing you with a seamless catering experience, and we're excited to support you in creating memorable events. Thank you for choosing <b>Mihanz Catering!</b>
        </div>
        <div class="w-50 fw-bold">
        <div class="mb-2">
          Best regards,
        </div>
        
       <!-- <div>
        [Your Name]
       </div> -->
        <!-- <div>
          [Your Position/Title]
        </div> -->
       
          <div class="mb-2">
            Mihanz Catering
          </div>
          <div class="mb-2">
            0926-563-1143 <br>
            0916-412-2250

          </div>
          <div class="d-flex justify-content-center"><a href="{{ url('/') }}" class="btn btn-lg btn-outline-primary m-5">Continue</a></div>
        </div>
      </div>
    </main>
  
</body>

</html>
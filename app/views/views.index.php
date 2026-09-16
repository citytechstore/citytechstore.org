<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require_once ('includes/cdn_header.php'); ?>
  <title>
  <?php echo APP_NAME; ?>
  </title>
</head>

<body>
  <?php require_once ('includes/header.php'); ?>
  <style>
    /* GLOBAL STYLES
-------------------------------------------------- */
    /* Padding below the footer and lighter body text */

    /* CUSTOMIZE THE CAROUSEL
-------------------------------------------------- */

    /* Carousel base class */
    .carousel {
      margin-bottom: 4rem;
    }

    /* Since positioning the image, we need to help out the caption */
    .carousel-caption {
      bottom: 3rem;
      z-index: 10;
    }

    /* Declare heights because of positioning of img element */
    .carousel-item {
      height: 32rem;
    }


    /* MARKETING CONTENT
-------------------------------------------------- */

    /* Center align the text within the three columns below the carousel */
    .marketing .col-lg-4 {
      margin-bottom: 1.5rem;
      text-align: center;
    }

    /* rtl:begin:ignore */
    .marketing .col-lg-4 p {
      margin-right: .75rem;
      margin-left: .75rem;
    }

    /* rtl:end:ignore */


    /* Featurettes
------------------------- */

    .featurette-divider {
      margin: 5rem 0;
      /* Space out the Bootstrap <hr> more */
    }

    /* Thin out the marketing headings */
    /* rtl:begin:remove */
    .featurette-heading {
      letter-spacing: -.05rem;
    }

    /* rtl:end:remove */

    /* RESPONSIVE CSS
-------------------------------------------------- */

    @media (min-width: 40em) {

      /* Bump up size of carousel content */
      .carousel-caption p {
        margin-bottom: 1.25rem;
        font-size: 1.25rem;
        line-height: 1.4;
      }

      .featurette-heading {
        font-size: 50px;
      }
    }

    @media (min-width: 62em) {
      .featurette-heading {
        margin-top: 7rem;
      }
    }
  </style>
  
  <!-- carousel -->
  <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
      <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3" class="active"
        aria-current="true"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item">
        <img src="assets/img/pexels-gdtography-277628-911738.jpg" alt="" class="img w-100 vh-100">
        <div class="container">
          <div class="carousel-caption text-dark">
            <h1 class=""><?php echo APP_NAME; ?>: Upgrade Your Digital Arsenal</h1>
            <p class="opacity-75">Elevate your productivity and entertainment with our range of high-performance
              gadgets. Unleash the potential of your digital world with <?php echo APP_NAME; ?>'s curated selection of devices.
            </p>
            <a class="btn btn-lg btn-primary me-3" href="/list?view=products">Explore</a>
            <a class="btn btn-lg btn-outline-primary" href="#contactus">Contact us</a>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <img src="assets/img/pexels-iriser-1366957.jpg" alt="" class="img w-100 vh-100">
        <div class="container">
          <div class="carousel-caption">
            <h1>Experience Cutting-Edge Innovation</h1>
            <p>Discover the latest in computing technology that empowers you to do more. From powerful laptops to sleek
              desktop setups, explore the future of computing with <?php echo APP_NAME; ?>.</p>
            <a class="btn btn-lg btn-primary me-3" href="/list?view=products">Explore</a>
            <a class="btn btn-lg btn-outline-primary" href="#contactus">Contact us</a>
          </div>
        </div>
      </div>
      <div class="carousel-item active">
        <img src="assets/img/pexels-kubiceknov-924824.jpg" alt="" class="img w-100 vh-100">
        <div class="container">
          <div class="carousel-caption">
            <h1>Welcome to <?php echo APP_NAME; ?></h1>
            <p>Your premier destination for all things tech. Dive into a world of innovation and convenience with our
              curated collection of computer gadgets. Experience the future today at <?php echo APP_NAME; ?>.</p>
            <a class="btn btn-lg btn-primary me-3" href="/list?view=products">Explore</a>
            <a class="btn btn-lg btn-outline-primary" href="#contactus">Contact us</a>
          </div>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  <!-- end of carousel -->


  <div class="container marketing">

    <!-- Three columns of text below the carousel -->
    <div class="row">
      <div class="col-lg-4">
        <img src="assets/img/icons/undraw_The_world_is_mine_re_j5cr.png" alt="" class="img rounded w-50">
        <h2 class="fw-normal">Seamless Connectivity</h2>
        <p>Experience uninterrupted connectivity with our range of networking solutions. Whether it's high-speed
          routers, Wi-Fi extenders, or Ethernet cables, stay connected with <?php echo APP_NAME; ?>.</p>
      </div>
      <div class="col-lg-4">
        <img src="assets/img/icons/undraw_horror_movie_3988.png" alt="" class="img rounded w-50">
        <h2 class="fw-normal">Immersive Entertainment</h2>
        <p>Dive into immersive entertainment experiences with our cutting-edge multimedia gadgets. From
          ultra-high-definition monitors to surround sound systems, elevate your entertainment setup with City Tech
          Store.</p>
      </div>
      <div class="col-lg-4">
        <img src="assets/img/icons/undraw_Dev_productivity_re_fylf.png" alt="" class="img rounded w-50">
        <h2 class="fw-normal">Effortless Productivity</h2>
        <p>Boost your productivity with our selection of productivity-enhancing gadgets. Discover ergonomic keyboards,
          precision mice, and multitasking monitors designed to streamline your workflow at <?php echo APP_NAME; ?>.</p>
      </div>
    </div>

    <div class="album py-5">
      <div class="container">

        <p class="fs-1 mb-5 text-center fw-bold">Available Products</p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/IMG-20240520-WA0019.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/61dsJm45m3L._AC_UY218_.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/51-+O3-wFxL._AC_UY218_.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/51bRSWrEc7S._AC_UY218_.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/IMG-20240520-WA0015.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/IMG-20240520-WA0016.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/IMG-20240520-WA0020.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/IMG-20240520-WA0018.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm">
              <img src="assets/img/c/IMG-20240520-WA0019.jpg" alt="" class="img w-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>

                  <small class="text-body-secondary">9 mins</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading fw-normal lh-1">Innovative Tech Solutions <span class="text-body-secondary">that
            will blow your mind.</span></h2>
        <p class="lead">Discover cutting-edge tech solutions designed to elevate your digital lifestyle. <?php echo APP_NAME; ?>
          offers a range of innovative products to enhance your everyday experiences.</p>
      </div>
      <div class="col-md-5">
        <img src="assets/img/icons/undraw_voice_control_ofo1.png" alt="" class="img w-100">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading fw-normal lh-1">Smart Home <span class="text-body-secondary">Essentials</span>
        </h2>
        <p class="lead">Transform your home with our smart home essentials. From intelligent lighting systems to
          automated security solutions, <?php echo APP_NAME; ?> helps you build a smarter, safer living space.</p>
      </div>
      <div class="col-md-5 order-md-1">
        <img src="assets/img/icons/undraw_smart_home_re_orvn.png" alt="" class="img w-100">
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading fw-normal lh-1">Gaming Gear <span class="text-body-secondary">Galore</span></h2>
        <p class="lead">Immerse yourself in the ultimate gaming experience with our selection of gaming gear. From
          high-performance gaming laptops to precision gaming mice, <?php echo APP_NAME; ?> has everything you need to dominate
          the virtual battlefield.</p>
      </div>
      <div class="col-md-5">
        <img src="assets/img/icons/undraw_video_game_night_8h8m.png" alt="" class="img w-100">
      </div>
    </div>

    <!-- <hr class="featurette-divider"> -->

    <!-- /END THE FEATURETTES -->


  </div>



  <!-- carousel 3x3 -->
  <div class="container mt-5">
    <h2 class="fs-2 text-center border-bottom py-2 mb-5"><?php echo APP_NAME; ?> Gallery</h2>
    <div class="row">
      <!-- First Row -->
      <div class="col-md-4">
        <div id="carousel1" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a1.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a2.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a3.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel1" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel1" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div id="carousel2" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a4.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a5.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a6.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel2" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel2" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div id="carousel3" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a7.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a8.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a9.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel3" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel3" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <!-- Second Row -->
      <div class="col-md-4">
        <div id="carousel4" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a10.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a11.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a12.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel4" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel4" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div id="carousel5" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a13.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a14.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a15.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel5" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel5" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div id="carousel6" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a16.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a17.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a18.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel6" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel6" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <!-- Third Row -->
      <div class="col-md-4">
        <div id="carousel7" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a19.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a20.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a21.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel7" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel7" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div id="carousel8" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a22.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a23.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a24.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel8" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel8" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div id="carousel9" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="assets/img/c/3x3/a25.jpg" class="d-block w-100" alt="Slide 1">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 1 Title</h5>
                            <p>Slide 1 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a26.jpg" class="d-block w-100" alt="Slide 2">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 2 Title</h5>
                            <p>Slide 2 Description</p>
                        </div> -->
            </div>
            <div class="carousel-item">
              <img src="assets/img/c/3x3/a27.jpg" class="d-block w-100" alt="Slide 3">
              <!-- <div class="carousel-caption d-none d-md-block">
                            <h5>Slide 3 Title</h5>
                            <p>Slide 3 Description</p>
                        </div> -->
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel9" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel9" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- address, contact, opening hours etc -->
  <div class="container mt-5" id="contactus">
    <div class="row">
      <div class="col-12">
        <h2 class="text-center mb-4">Contact Us</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <h3>Address</h3>
        <p><?php echo STORE_ADDRESS; ?></p>
      </div>
      <div class="col-md-4">
        <h3>Contact</h3>
        <p>Phone: <?php echo CONTACT_PHONE; ?><br> Email: <?php echo CONTACT_EMAIL; ?></p>
      </div>
      <div class="col-md-4">
        <h3>Opening Hours</h3>
        <p><?php echo STORE_OPENING_HOURS; ?></p>
      </div>
    </div>
  </div>

  <!-- About Us Section -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">
        <h2 class="text-center mb-4">About Us</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <h3>Welcome to <?php echo APP_NAME; ?></h3>
        <p>At <?php echo APP_NAME; ?>, we are passionate about bringing the latest and greatest in technology to our customers.
          Our mission is to provide top-quality computer gadgets and tech accessories that enhance your digital
          lifestyle, whether you're a professional, a gamer, or a tech enthusiast.</p>
      </div>
      <div class="col-md-6">
        <h3>Our Story</h3>
        <p>Founded in <?php echo FOUNDED_YEAR; ?>, <?php echo APP_NAME; ?> started with a simple idea: to create a one-stop shop for all your
          technology needs. With a team of tech-savvy professionals, we set out to offer a curated selection of
          innovative products that combine functionality, quality, and style.</p>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-md-6">
        <h3>What We Offer</h3>
        <ul>
          <li><strong>Wide Range of Products:</strong> From high-performance laptops and desktop computers to the latest
            in smart home technology and gaming gear, we have something for everyone.</li>
          <li><strong>Expert Support:</strong> Our knowledgeable staff is always ready to assist you with any questions
            or technical support you may need. We believe in providing personalized service to help you make the best
            choices.</li>
          <li><strong>Competitive Prices:</strong> We strive to offer the best value for your money, with competitive
            pricing on all our products. We regularly update our inventory to include the newest releases and best
            deals.</li>
        </ul>
      </div>
      <div class="col-md-6">
        <h3>Our Commitment</h3>
        <p>At <?php echo APP_NAME; ?>, we are committed to providing an exceptional shopping experience. We prioritize customer
          satisfaction and aim to build long-term relationships with our customers by offering reliable products and
          outstanding service. Our goal is to be your trusted partner in navigating the ever-evolving world of
          technology.</p>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-md-6">
        <h3>Visit Us</h3>
        <p>Come visit our store located at <?php echo STORE_ADDRESS; ?>, and explore our wide range of products. Our store
          is open <?php echo STORE_OPENING_HOURS; ?>. You can also shop with
          us online and enjoy fast, reliable shipping straight to your door.</p>
      </div>
      <div class="col-md-6">
        <h3>Join Our Community</h3>
        <p>Follow us on social media to stay updated on the latest tech trends, special offers, and events. Join the
          <?php echo APP_NAME; ?> community and be a part of our journey towards a smarter, more connected world.</p>
        <p>Thank you for choosing <?php echo APP_NAME; ?>. We look forward to serving you!</p>
      </div>
    </div>
  </div>

  <!-- End About Us Section -->
  <?php require_once ('includes/footer.php'); ?>
</body>
<?php require_once ('includes/cdn_footer.php'); ?>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>Mengenali Alpaca Lebih Jauh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light" style="padding-top: 60px; background-image: url('img/background.png'); background-size: cover;">
    @include('partials.header')

    <main>
    <div id="carouselExampleIndicators" class="carousel slide mt-0" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner" style="max-height: 600px;">
            <div class="carousel-item active">
                <img src="img/1.jpg" class="d-block w-100" alt="Slide 1" style="height: 600px; object-fit: cover;">
                <div class="container">
                    <div class="carousel-caption text-start">
                    <h3>Teknologi tidak hanya sebatas penemuan</h1>
                    <p class="opacity-75">Teknologi dapat berarti perkembangan dalam melakukan sesuatu.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/3.jpg" class="d-block w-100" alt="Slide 2" style="height: 600px; object-fit: cover;">
                <div class="container">
                    <div class="carousel-caption text-start">
                    <h3>Tidak ada kata terlalu cepat atau terlambat dalam belajar teknologi</h1>
                    <p class="opacity-75">Belajar teknologi membuatmu berpikir kritis dan terbuka.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/2.jpg" class="d-block w-100" alt="Slide 3" style="height: 600px; object-fit: cover;">
                <div class="container">
                    <div class="carousel-caption text-start">
                    <h3>Gabung Sekarang.</h1>
                    <p class="opacity-75">Bergabunglah bersama kami dan tingkatkan kemampuan dalam berteknologi</p>
                    <p><a class="btn rounded-login-btn" href="/register"><i class="bi bi-box-arrow-in-right"></i> Coba Sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="about-us" class="container marketing mt-5">
        <div class="row">
            <div class="col-lg-4">  
                <h2>Alpha Path Academy</h2>
                <p>Di era digital yang berkembang pesat, teknologi bukan lagi pilihan—melainkan kebutuhan...</p>
                <a href="#introduction" class="btn rounded-login-btn">Baca Selengkapnya</a>
            </div>
            <div class="col-lg-4">
                <h2>Tim Kami</h2>
                <p>Kami adalah tim yang terdiri dari pendidik dan mahasiswa teknologi dari Putera Indonesia YPTK Padang...</p>
                <a href="#our-team" class="btn rounded-login-btn">Baca Selengkapnya</a>
            </div>
            <div class="col-lg-4">
                <h2>Pengalaman Kami</h2>
                <p>Kami bukan hanya tim pengembang dan pendidik, tetapi juga telah memiliki pengalaman langsung dalam mengajar teknologi...</p>
                <a href="#experiences" class="btn rounded-login-btn">Baca Selengkapnya</a>
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="row featurette">
            <div id="introduction" class="col-md-7">
                <h2>Welcome to Alpha Path Academy</h2>
                <p>Alpha Path Academy atau disingkat Alpaca, merupakan sebuah website pembelajaran yang memiliki berbagai macam jenis kursus dan modul di dalamnya.</p><p> Di Alpha Path Academy, kami percaya bahwa setiap anak harus memiliki kesempatan untuk mengembangkan keterampilan digital sejak usia dini. Platform kami dirancang khusus untuk memperkenalkan dunia teknologi kepada anak-anak dengan cara yang menyenangkan dan interaktif. 
                </p><p>Kami mengajarkan dasar-dasar pemrograman, berpikir logis, dan pemecahan masalah dengan metode yang mudah dipahami oleh anak-anak. Dengan cara ini, kami membantu mereka tidak hanya untuk masa depan di bidang teknologi, tetapi juga dalam kehidupan sehari-hari yang semakin bergantung pada literasi digital. Dengan pelajaran interaktif, proyek langsung, dan kurikulum bertahap, kami membangun fondasi yang kuat agar anak-anak dapat menjelajahi, bereksperimen, dan berkembang di dunia teknologi.</p>
            </div>
        </div>
        <hr class="featurette-divider">

        <div id="our-team" class="row featurette">
        <p>Mari Kenali AT Academy Team Lebih jauh</p>
            <div class="col-md-7">
                <h3>Ikraam Agusta</h3>
                <p>Pengembang dan pembuat website Alpha Path Academy.</p>
                <p>Mahasiswa tingkat akhir yang memiliki minat dalam dunia teknologi dan berkontribusi dalam pengembangan platform edukasi berbasis teknologi.</p>
            </div>
            <div class="col-md-5 text-center">
                <img src="img/image(ikraam.).jpg" class="img-fluid rounded shadow p-1" alt="Ikraam Agusta" style="max-width: 250px; max-height: 250px;">
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="row featurette">
            <div class="col-md-7 order-md-2">
                <h3>Muhammad Habil Alfarisy</h3>
                <p>Pencetus nama dan ide dari Alpha Team.</p>
                <p>Memiliki visi dalam membangun platform edukasi yang inovatif dan membantu anak-anak dalam menghadapi dunia teknologi.</p>
            </div>
            <div class="col-md-5 order-md-1 text-center">
                <img src="img/image(habil.).jpg" class="img-fluid rounded shadow p-1" alt="Muhammad Habil Alfarisy" style="max-width: 250px; max-height: 250px;">
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="row featurette">
            <div class="col-md-7">
                <h3>Imam Rahmad</h3>
                <p>Salah satu anggota tim yang berperan dalam penyampaian materi pembelajaran. Merupakan seorang keturunan cina-pariaman yang sangat mendedikasikan dirinya untuk menjadi seorang MC Arsip Biru. Agar bisa mengajak kencan seluruh murid yang ada di SMU Ganeha terutama Shiroko. Beliau juga memiliki Waifu kapal yang sangat dicintainya. Bahkan beliau ingin pergi ke Isekai dan bertemu dengan para waifunya.</p>
            </div>
            <div class="col-md-5 text-center">
                <img src="img/image(imam.).jpg" class="img-fluid rounded shadow p-1" alt="Imam Rahmad" style="max-width: 250px; max-height: 250px;">
            </div>
        </div>
        <hr class="featurette-divider">
        <div class="row featurette">
            <div class="col-md-7 order-md-2">
                <h3>Billy Hendrik, S.Kom, M.Kom, Ph.D</h3>
                <p>Seorang mentor dan pendidik berpengalaman dari Universitas Putera Indonesia YPTK Padang yang membimbing tim dalam pengembangan platform edukasi.</p>
            </div>
            <div class="col-md-5 order-md-1 text-center">
                <img src="img/Image(pak billy.).jpg" class="img-fluid rounded shadow p-1" alt="Billy Hendrik" style="max-width: 250px; max-height: 250px;">
            </div>
        </div>
        <hr class="featurette-divider">
        <div id="experiences" class="row featurette">
            <h2>Pengalaman Kami dalam Mengajar</h2>    
            <div class="col-md-7">
                <p>Mengajarkan teknologi kepada anak-anak adalah pengalaman yang luar biasa bagi kami. Di SD Adzkia 1 Padang, kami memperkenalkan dunia robotika, Arduino, ESP32, Internet of Things (IoT), dan web server kepada para siswa sejak dini. Dengan metode yang menyenangkan dan interaktif, kami membuktikan bahwa anak-anak mampu memahami konsep teknologi yang sering dianggap sulit, asalkan diajarkan dengan cara yang tepat.</p>
                <p>Kami memulai dengan dasar-dasar robotika, di mana siswa belajar tentang komponen robot dan cara mengontrolnya. Dengan menggunakan Arduino dan ESP32, mereka mulai memahami bagaimana perangkat keras dan perangkat lunak bekerja bersama untuk menciptakan sistem yang cerdas. Kami juga mengenalkan konsep Internet of Things (IoT), yang memungkinkan mereka menghubungkan perangkat ke internet untuk berbagai keperluan, seperti mengontrol robot dari jarak jauh atau mengumpulkan data sensor secara real-time<p>
                <p>Melihat antusiasme dan rasa ingin tahu mereka adalah kepuasan tersendiri bagi kami. Pengalaman ini semakin memperkuat komitmen kami untuk terus mengembangkan metode pembelajaran teknologi yang ramah anak.</p>
                <p>Ayo Bergabung bersama Kami!</p>
            </div>
            <div class="col-md-5 text-center">
                <img src="img/joinus.jpg" class="img-fluid rounded shadow p-1" alt="our experiences" style="max-width: 500px; max-height: 350px;">
            </div>
        </div>
    </div>
    </main>
@include('partials.footer')
</body>
</html>

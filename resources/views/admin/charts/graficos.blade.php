@extends('admin.panel_admin')

@section('contenido')
<section class="">
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Iniciativas</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="iniciativasChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Organizaciones</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="organizacionesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Inversión</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="inverisionesChart"></canvas>
                    </div>
                </div>
            </div>


            <div class="col-12 col-md-6 col-lg-6 container">
                <h2>ODS relacionados</h2>
                <div class="card-group">
                    <div class="card bg-success">
                        <div class="card-body text-center text-white">
                            <p class="card-text">Argentina won convincingly!</p>
                        </div>
                    </div>
                    <div class="card bg-primary">
                        <div class="card-body text-center">
                            <p class="card-text">Demo Text!</p>
                        </div>
                    </div>
                    <div class="card bg-warning">
                        <div class="card-body text-center text-white">
                            <p class="card-text">Do not cross!</p>
                        </div>
                    </div>
                    <div class="card bg-secondary">
                        <div class="card-body text-center">
                            <p class="card-text">I did it!</p>
                        </div>
                    </div>
                    <div class="card bg-info">
                        <div class="card-body text-center">
                            <p class="card-text">It worked!</p>
                        </div>
                    </div>
                </div>
                <div class="card-group">
                    <div class="card bg-warning">
                        <div class="card-body text-center text-white">
                            <p class="card-text">Argentina won convincingly!</p>
                        </div>
                    </div>
                    <div class="card bg-dark">
                        <div class="card-body text-center">
                            <p class="card-text">Demo Text!</p>
                        </div>
                    </div>
                    <div class="card bg-white">
                        <div class="card-body text-center text-white">
                            <p class="card-text">Do not cross!</p>
                        </div>
                    </div>
                    <div class="card bg-info">
                        <div class="card-body text-center">
                            <p class="card-text">I did it!</p>
                        </div>
                    </div>
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <p class="card-text">It worked!</p>
                        </div>
                    </div>
                </div>
                <div class="card-group">
                    <div class="card bg-success">
                        <div class="card-body text-center text-white">
                            <p class="card-text">Argentina won convincingly!</p>
                        </div>
                    </div>
                    <div class="card bg-primary">
                        <div class="card-body text-center">
                            <p class="card-text">Demo Text!</p>
                        </div>
                    </div>
                    <div class="card bg-warning">
                        <div class="card-body text-center text-white">
                            <p class="card-text">Do not cross!</p>
                        </div>
                    </div>
                    <div class="card bg-success">
                        <div class="card-body text-center">
                            <p class="card-text">I did it!</p>
                        </div>
                    </div>
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <p class="card-text">It worked!</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>



        <!-- en esta seccion iran los div que almacenaran los objetivos ligados a las iniciativas -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <div class="col">
                <div class="card">
                    <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/041.webp" class="card-img-top" alt="Hollywood Sign on The Hill">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">
                            This is a longer card with supporting text below as a natural lead-in to
                            additional content. This content is a little bit longer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/042.webp" class="card-img-top" alt="Palm Springs Road">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">
                            This is a longer card with supporting text below as a natural lead-in to
                            additional content. This content is a little bit longer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/043.webp" class="card-img-top" alt="Los Angeles Skyscrapers">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to
                            additional content.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/044.webp" class="card-img-top" alt="Skyscrapers">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">
                            This is a longer card with supporting text below as a natural lead-in to
                            additional content.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/046.webp" class="card-img-top" alt="Skyscrapers">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">
                            This is a longer card with supporting text below as a natural lead-in to
                            additional content. This content is a little bit longer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <img src="https://mdbcdn.b-cdn.net/img/new/standard/city/050.webp" class="card-img-top" alt="Skyscrapers">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">
                            This is a longer card with supporting text below as a natural lead-in to
                            additional content. This content is a little bit longer.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

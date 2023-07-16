@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#content1">Send To
                                Whastapp</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#content2">Send To Email</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="content1">
                            <h5 class="card-title">Send To Whatsapp</h5>
                            <form action="{{route('contact.send-to-whatsapp') }}" method="post" role="form" class="">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group" data-aos="fade-up">
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6 form-group mt-3 mt-md-0" data-aos="fade-up">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Your Email" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3" data-aos="fade-up">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Subject" required>
                                </div>
                                <div class="form-group mt-3" data-aos="fade-up">
                                    <textarea class="form-control" name="message" rows="5" placeholder="Message"
                                        required></textarea>
                                </div>
                                <div class="text-center" data-aos="fade-up"><button type="submit"
                                        class="btn btn-primary mt-3">Send Message</button></div>
                            </form><!-- End Contact Form -->
                        </div>
                        <div class="tab-pane fade" id="content2">
                            <h5 class="card-title">Send To Email</h5>
                            <form action="{{route('contact.send-to-email') }}" method="post" role="form" class="">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group" data-aos="fade-up">
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6 form-group mt-3 mt-md-0" data-aos="fade-up">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Your Email" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3" data-aos="fade-up">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Subject" required>
                                </div>
                                <div class="form-group mt-3" data-aos="fade-up">
                                    <textarea class="form-control" name="message" rows="5" placeholder="Message"
                                        required></textarea>
                                </div>
                                <div class="text-center" data-aos="fade-up"><button type="submit"
                                        class="btn btn-primary mt-3">Send Message</button></div>
                            </form><!-- End Contact Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

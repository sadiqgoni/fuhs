<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
{{--    @include('layouts.partials.header')--}}
    <style>

        svg{
            display: none;
        }
        .faq h3 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .faq p {
            margin: 0 0 1rem;
        }

       #qf{
           position: fixed;max-width: 400px !important;right: 0;bottom:0;
       }
       @media (max-width: 920px) {
           #qf{
               position: relative;max-width: 400px !important;right: 0;
               margin-top: 30px;
           }
       }




        .faq-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .faq-header h2 {
            font-weight: 700;
            color: #2e2e2e;
            position: relative;
            padding-bottom: 7px;
        }

        .faq-header h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-color);
        }

        .faq-header p {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 700px;
            margin: 0 auto;
        }

        .faq-card {
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .faq-card .card-header {
            background-color: white;
            border-bottom: none;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-card .card-header:hover {
            background-color: var(--secondary-color);
        }

        .faq-card .card-header h5 {
            margin-bottom: 0;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-card .card-header h5 .icon {
            transition: all 0.3s ease;
        }

        .faq-card .card-header h5[aria-expanded="true"] .icon {
            transform: rotate(180deg);
        }

        .faq-card .card-body {
            padding: 20px;
            background-color: var(--secondary-color);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .faq-section {
            padding: 30px 0;
            background-color: white;
        }

        .faq-search {
            max-width: 500px;
            margin: 0 auto 40px;
            position: relative;
        }

        .faq-search input {
            padding-left: 45px;
            border-radius: 50px;
            height: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e3e6f0;
        }

        .faq-search i {
            position: absolute;
            left: 20px;
            top: 15px;
            color: #d1d3e2;
        }
    </style>

    @guest
        <h5 class="text-center text-uppercase" style="margin-top: 80px;color: black">Help Center</h5>
        <hr>
    @endguest
    <div class="container mt-4">
        <select name="" wire:model.live="selectedTopicId" id="" class="form-control-sm my-2" style="max-width: 200px !important;">
            <option value="">Select Topic</option>
            @forelse(\App\Models\HelpTopic::all() as $topic)
                <option value="{{$topic->id}}" selected>{{$topic->topic}}</option>
            @empty

            @endforelse
        </select>
        <div class="bg-light p-4 rounded shadow-sm mb-3">

            @if(!empty($pages))
                <p>{{ $this->currentPageText }}</p>
                @if($ids)
                    @php
                        $vid=\App\Models\help::where('topic_id',$ids)->first();

                    @endphp
                    @if ($vid->video_file != null)
                        <video width="640" height="360" controls>
                            <source src="{{asset('storage/'.$vid->video_file)}}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                @endif


            @else
                <p class="text-muted">...</p>
            @endif
        </div>

        <div class="d-flex justify-content-between">
            <button wire:click="prevPage" class="btn btn-outline-primary" @if($currentPage === 0) disabled @endif>← Previous</button>
            <button wire:click="nextPage" class="btn btn-outline-primary" @if($currentPage === count($pages) - 1) disabled @endif>Next →</button>
        </div>



        <div class="container py-3">
            <div class="row">
                <div class="col col-md-9">


                    <section class="faq-section">
                        <div class="container">
                            <div class="faq-header">
                                <h2>Frequently Asked Questions</h2>
                                {{--                            <p class="mt-3">Find quick answers to common questions about our products and services below. Can't find what you're looking for? Contact our support team.</p>--}}
                            </div>

                            {{--                        <div class="faq-search">--}}
                            {{--                            <i class="fas fa-search"></i>--}}
                            {{--                            <input type="text" class="form-control" placeholder="Search FAQs...">--}}
                            {{--                        </div>--}}

                            <div id="accordion" class="faq-accordion">
                                <!-- FAQ Item 1 -->
                                @forelse($faqs as $key=>$faq)
                                    <div class="card faq-card">
                                        <div class="card-header" id="heading{{$key}}" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                            <h5 class="mb-0">
                                                {{$faq->questions}}
                                                <i class="fas fa-chevron-down icon"></i>
                                            </h5>
                                        </div>
                                        <div id="collapse{{$key}}" class="collapse " aria-labelledby="heading{{$key}}" data-parent="#accordion">
                                            <div class="card-body">
                                                {{$faq->answers}}
                                            </div>
                                        </div>
                                    </div>
                                @empty

                                @endforelse
                                {{$faqs->render()}}

                            </div>


                        </div>
                    </section>



                    <script>
                        // Optional: Add search functionality
                        $(document).ready(function(){
                            $('.faq-search input').on('keyup', function(){
                                var searchTerm = $(this).val().toLowerCase();
                                $('.faq-card').each(function(){
                                    var question = $(this).find('.card-header h5').text().toLowerCase();
                                    var answer = $(this).find('.card-body').text().toLowerCase();

                                    if(question.indexOf(searchTerm) !== -1 || answer.indexOf(searchTerm) !== -1) {
                                        $(this).show();
                                    } else {
                                        $(this).hide();
                                    }
                                });
                            });
                        });
                    </script>

                </div>
                <div class="card shadow border-0" id="qf">
                    <div class="card-body p-4">
                        <h4 class="mb-3 text-primary text-center">
                            Can't Find Your Question?
                        </h4>
                        {{--                    <p class="text-muted text-center mb-4">--}}
                        {{--                        Submit your question below and our support team will respond shortly.--}}
                        {{--                    </p>--}}

                        <form wire:submit.prevent="submit">
                            <div class="mb-3">
                                <label for="questionTitle" class="form-label">Question Title</label>
                                <input type="text" wire:model.live="title" class="form-control @error('title') is-invalid @enderror" id="questionTitle" placeholder="e.g. How do I Login?" required>
                            </div>


                            {{--                        <div class="mb-3">--}}
                            {{--                            <label for="userEmail" class="form-label">Your Email (optional)</label>--}}
                            {{--                            <input type="email" wire:model="email" class="form-control" id="userEmail" placeholder="Enter your email if you want a direct response">--}}
                            {{--                        </div>--}}

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-1"></i> Submit Question
                            </button>
                        </form>

                        <!-- Optional success message -->
                        <!-- <div class="alert alert-success mt-3" role="alert">
                          Thank you! Your question has been submitted.
                        </div> -->

                    </div>
                </div>

            </div>

        </div>
    </div>
    @guest
{{--        @include('layouts.partials.footer')--}}
    @endguest


    @section('title')
        Help Center
    @endsection
    @section('page_title')
        Help Center
    @endsection
</div>

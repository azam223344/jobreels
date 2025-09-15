<style>
    .smaller-image {
        width: 75px; /* Set your desired width */
        height: 75px; /* Set your desired height */
        overflow: hidden;
        margin: 0 auto 10px; /* Add some margin at the bottom */
    }
    
    .smaller-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .custom-bg-gray {
        background-color: #ccc; /* Replace with your desired gray color */
    }
    
    
        </style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mt-1 mb-2 content-header-left col-12">
                    <div class="breadcrumbs-top">
                        <h5 class="float-left pr-1 mb-0 content-header-title">Job Seekers</h5>
                        <div class="breadcrumb-wrapper d-none d-sm-block">
                            <ol class="p-0 pl-1 mb-0 breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Pending
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 ">
                    <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-9 ">
                    <div class="row">
                        {{-- <livewire:tables.freelancers-table /> --}}
                        @foreach($users as $user)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 mb-2 mr-4">
                            <div class="card" style="height:350px;width:250px;">
                                <a href="{{url('/')}}/freelancers/profile/?user_id={{$user->id}}" class="card-link" style="text-decoration: none; color: inherit;">
                                    <div class="card-body text-center">
                                        <div class="image-wrapper">
                                            <div class="rounded-circle smaller-image">
                                                <img src="{{$user->getProfilePictureUrlAttribute()}}" alt="Card image cap">
                                            </div>
                                        </div>
                                        <h5 class="card-title">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                        <p class="card-text">{{$user->freelancer->verification_level == 'Verified' ? 'Unverified' : 'Verified'}}</p>
                                        <p class="card-text">{{ $user->email }}</p>
                                        <p class="card-text"> @if (!empty($user->phone) && is_numeric($user->phone))
                                            {{ number_format($user->phone, 0, '', '-') }}
                                        @else
                                            N/A <!-- or any other suitable message for empty/invalid phone numbers -->
                                        @endif</p>
                                        <p class="card-text">{{ $user->state }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    
                    
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-3">
            <div class="card card_height_100 mb-30">
                <div class="card-body">
                    <!-- ApexCharts Circular Chart -->
                    <div class="chart-container">
                        <div id="chart-verified-users" style="min-height: 300px;"></div>
                    </div>
        
                    <!-- User Status Cards -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card custom-bg-gray">
                                <div class="card-body text-center"> <!-- Added 'text-center' class here -->
                                    <h5 class="card-title">Approved</h5>
                                    <p class="card-text">{{$pendingFreelancers}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card custom-bg-gray">
                                <div class="card-body text-center"> <!-- Added 'text-center' class here -->
                                    <h5 class="card-title">Pending</h5>
                                    <p class="card-text">{{$approvedFreelancers}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        
    </div>
    </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            var totalFreelancers = @json($totalFreelancers);
            var approvedFreelancers = @json($approvedFreelancers);
            
            var percentage = Math.round((approvedFreelancers / totalFreelancers) * 100);
    
            var options = {
                chart: {
                    type: 'radialBar',
                    height: '100%', // Set chart height to 100% for responsiveness
                    width: '100%',  // Set chart width to 100% for responsiveness
                },
                series: [percentage],
                labels: ['Pending'],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '70%',
                        },
                    },
                },
            };
    
            var chart = new ApexCharts(document.querySelector("#chart-verified-users"), options);
            chart.render();
        });
    </script>
    
    
    
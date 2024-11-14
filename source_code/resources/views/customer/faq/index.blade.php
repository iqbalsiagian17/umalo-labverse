@extends('layouts.customer.master')
@section('content')
<div class="container mt-5 mb-5">
    <h2 class="mb-4 text-center">Frequently Asked Questions (FAQ)</h2>
    <div class="accordion" id="faqAccordion">
        @foreach($faq as $index => $item)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header text-white bg-primary" id="heading{{ $index }}">
                <h5 class="mb-0">
                    <button class="btn btn-link d-flex justify-content-between align-items-center w-100 text-decoration-none text-white" type="button" data-toggle="collapse" data-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                        <span>{{ $item->question }}</span>
                        <i class="fas {{ $index == 0 ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                    </button>
                </h5>
            </div>
            <div id="collapse{{ $index }}" class="collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-parent="#faqAccordion">
                <div class="card-body">
                    {{ $item->answer }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .accordion .card-header {
        cursor: pointer;
    }

    .accordion .card-header:hover {
        background-color: #416bbf; /* Slightly darker primary color */
    }

    .accordion .card-body {
        font-size: 1rem; /* Adjust font size */
        line-height: 1.6; /* Improve readability */
    }

    .accordion .btn-link {
        text-align: left;
        padding-left: 0;
        color: #fff; /* Keep text white */
    }

    .accordion .btn-link:hover {
        text-decoration: none;
        color: #f8f9fa; /* Slightly lighter on hover */
    }

    .accordion .card {
        border-radius: 10px; /* Rounded corners */
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accordionButtons = document.querySelectorAll('#faqAccordion .btn-link');

        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Toggle the arrow icons
                const icon = this.querySelector('i');
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                // Remove all up arrow icons
                document.querySelectorAll('#faqAccordion .btn-link i').forEach(i => {
                    i.classList.remove('fa-chevron-up');
                    i.classList.add('fa-chevron-down');
                });

                if (isExpanded) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });
    });
</script>
@endsection

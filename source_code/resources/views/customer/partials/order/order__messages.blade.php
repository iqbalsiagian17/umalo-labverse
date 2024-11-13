@if (session('success'))
    <div id="fixedSuccessAlert" class="alert alert-dismissible fade show d-flex align-items-center" role="alert" 
         style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); padding: 12px 16px; border-radius: 6px; position: fixed; top: 20px; right: 20px; z-index: 1050; min-width: 250px; animation: fadeIn 0.5s ease;">
        <i class="fas fa-check-circle me-2" style="font-size: 1.3em; color: #0f5132;"></i>
        <span style="flex: 1; font-size: 0.95em; padding-left: 10px;">
            {{ session('success') }}
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" 
                style="background: none; border: none; font-size: 1em; color: #0f5132; cursor: pointer;">
            &times;
        </button>
    </div>

    <style>
        /* Animation for fading in the notification */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation for fading out the notification */
        @keyframes fadeOut {
            0% {
                opacity: 1;
                transform: translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* Button hover effect for close icon */
        .btn-close:hover {
            color: #0c3924;
        }
    </style>

    <script>
        // Automatically hide the alert after 5 seconds
        setTimeout(function() {
            let alert = document.getElementById('fixedSuccessAlert');
            if (alert) {
                alert.style.animation = 'fadeOut 0.5s ease';
                setTimeout(() => alert.remove(), 500); // Remove from DOM after fade out
            }
        }, 5000);
    </script>
@endif
@if(session('welcome_message'))
    <div id="welcomeMessage" class="alert alert-dismissible fade show d-flex align-items-center" role="alert"
         style="background: linear-gradient(135deg, #42378C, #7A57D1); color: white; border-left: 6px solid #352D70; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); padding: 15px 20px; border-radius: 10px; animation: slide-in 0.5s ease; position: fixed; top: 20px; right: 20px; z-index: 1050; min-width: 250px;">
         <i class="fas fa-smile-beam me-3" style="font-size: 1.5em; color: #ffffff;"></i>
         <div style="flex: 1; padding-left: 10px;">
             <strong>{{ session('welcome_message') }}</strong>
         </div>
         
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                style="background: none; border: none; font-size: 1.5em; color: white; margin-left: 10px; cursor: pointer;">
            &times;
        </button>
    </div>

    <style>
        /* Animation for sliding in the notification */
        @keyframes slide-in {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Button hover effect for close icon */
        .btn-close:hover {
            color: #ddd;
            transform: scale(1.1);
        }
    </style>

    <script>
        // Automatically hide the alert after 5 seconds
        setTimeout(function() {
            let alert = document.getElementById('welcomeMessage');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500); // Remove from DOM after fade out
            }
        }, 5000);
    </script>
@endif

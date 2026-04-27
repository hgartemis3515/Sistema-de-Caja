<body>
<style>
.videotutoriales_superadmin_container {
    max-width: 1400px;
    margin: 30px auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    padding: 0 20px;
}

.videotutoriales_superadmin_header {
    background: linear-gradient(135deg, #4B58A5 0%, #6875CF 100%);
    padding: 40px;
    border-radius: 20px;
    color: white;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(75, 88, 165, 0.2);
    position: relative;
    overflow: hidden;
}

.videotutoriales_superadmin_header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 300px;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 50%;
    transform: translate(150px, -150px);
}

.videotutoriales_superadmin_header h2 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.videotutoriales_superadmin_header p {
    margin: 15px 0 0;
    font-size: 16px;
    line-height: 1.6;
    opacity: 0.9;
    max-width: 600px;
}

.videotutoriales_superadmin_grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    padding: 0 10px;
}

.videotutoriales_superadmin_card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.videotutoriales_superadmin_card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15);
}

.videotutoriales_superadmin_thumbnail {
    position: relative;
    padding-top: 56.25%;
    background: #f5f5f5;
    cursor: pointer;
    overflow: hidden;
}

.videotutoriales_superadmin_thumbnail img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.videotutoriales_superadmin_card:hover .videotutoriales_superadmin_thumbnail img {
    transform: scale(1.05);
}

.videotutoriales_superadmin_play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    width: 70px;
    height: 70px;
    background: rgba(75, 88, 165, 0.95);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    opacity: 0;
}

.videotutoriales_superadmin_card:hover .videotutoriales_superadmin_play {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
}

.videotutoriales_superadmin_play::after {
    content: '';
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 12px 0 12px 22px;
    border-color: transparent transparent transparent white;
    margin-left: 5px;
}

.videotutoriales_superadmin_content {
    padding: 25px;
}

.videotutoriales_superadmin_title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 15px;
    color: #1a1a1a;
    line-height: 1.4;
}

.videotutoriales_superadmin_duration {
    font-size: 14px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.videotutoriales_superadmin_duration svg {
    color: #4B58A5;
}

.videotutoriales_superadmin_modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 1000;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.videotutoriales_superadmin_modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 1200px;
    background: #1a1a1a;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}

.videotutoriales_superadmin_close {
    position: absolute;
    top: -50px;
    right: 0;
    color: white;
    font-size: 20px;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.videotutoriales_superadmin_close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.videotutoriales_superadmin_video-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    background: #000;
}

.videotutoriales_superadmin_video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.videotutoriales_superadmin_modal-info {
    padding: 25px;
    color: white;
}

.videotutoriales_superadmin_modal-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 10px;
}

.videotutoriales_superadmin_modal-description {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
}

@media (max-width: 768px) {
    .videotutoriales_superadmin_header {
    padding: 30px;
    }

    .videotutoriales_superadmin_header h2 {
    font-size: 24px;
    }

    .videotutoriales_superadmin_grid {
    grid-template-columns: 1fr;
    }

    .videotutoriales_superadmin_modal-content {
    width: 95%;
    }
}

/* Loading animation */
.videotutoriales_superadmin_loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    border: 3px solid rgba(75, 88, 165, 0.3);
    border-radius: 50%;
    border-top-color: #4B58A5;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

.videotutoriales_superadmin_back_button {
      position: fixed;
      left: 20px;
      top: 20px;
      background: #4B58A5;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      z-index: 100;
      box-shadow: 0 4px 12px rgba(75, 88, 165, 0.2);
    }

    .videotutoriales_superadmin_back_button:hover {
      background: #3B4785;
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(75, 88, 165, 0.3);
    }

    .videotutoriales_superadmin_container {
      max-width: 1400px;
      margin: 30px auto;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
      padding: 60px 20px 20px;
    }

    .videotutoriales_superadmin_header {
      background: linear-gradient(135deg, #4B58A5 0%, #6875CF 100%);
      padding: 40px;
      border-radius: 20px;
      color: white;
      margin-bottom: 40px;
      box-shadow: 0 10px 30px rgba(75, 88, 165, 0.2);
      position: relative;
      overflow: hidden;
    }

    .videotutoriales_superadmin_grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
      padding: 0 10px;
    }

    .videotutoriales_superadmin_card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Mejoras responsive */
    @media (max-width: 768px) {
      .videotutoriales_superadmin_container {
        padding: 80px 15px 15px;
      }

      .videotutoriales_superadmin_header {
        padding: 25px;
        margin-bottom: 25px;
        text-align: center;
      }

      .videotutoriales_superadmin_header h2 {
        font-size: 24px !important;
      }

      .videotutoriales_superadmin_header p {
        font-size: 14px !important;
      }

      .videotutoriales_superadmin_grid {
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 0;
      }

      .videotutoriales_superadmin_card {
        margin: 0 auto;
        max-width: 400px;
      }

      .videotutoriales_superadmin_title {
        font-size: 16px !important;
      }

      .videotutoriales_superadmin_modal-content {
        width: 95% !important;
        margin: 0 auto;
      }

      .videotutoriales_superadmin_modal-info {
        padding: 15px !important;
      }

      .videotutoriales_superadmin_modal-title {
        font-size: 18px !important;
      }

      .videotutoriales_superadmin_modal-description {
        font-size: 14px !important;
      }

      .videotutoriales_superadmin_close {
        top: 10px !important;
        right: 10px !important;
        background: rgba(0, 0, 0, 0.5) !important;
      }

      .videotutoriales_superadmin_back_button {
        left: 50%;
        transform: translateX(-50%);
        top: 15px;
        padding: 8px 16px;
        font-size: 14px;
      }

      .videotutoriales_superadmin_back_button:hover {
        transform: translateX(-50%) translateY(-2px);
      }
    }

    @media (max-width: 480px) {
      .videotutoriales_superadmin_container {
        padding: 70px 10px 10px;
      }

      .videotutoriales_superadmin_header {
        padding: 20px;
        margin-bottom: 20px;
      }

      .videotutoriales_superadmin_card {
        max-width: 100%;
      }
    }

    /* Mejoras para tablets */
    @media (min-width: 769px) and (max-width: 1024px) {
      .videotutoriales_superadmin_grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .videotutoriales_superadmin_container {
        padding: 90px 20px 20px;
      }
    }
</style>

    <a class="videotutoriales_superadmin_back_button" href="/facturacionv8/gestiondecontribuyentes">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    Regresar
  </a>

  <div class="videotutoriales_superadmin_container">
    <div class="videotutoriales_superadmin_header">
      <h2>Video Tutoriales</h2>
      <p>Descubre todas las funcionalidades de nuestro sistema a través de guías detalladas y profesionales. Aprende a maximizar el potencial de cada módulo con nuestros tutoriales paso a paso.</p>
    </div>
    
    <div class="videotutoriales_superadmin_grid" id="videotutoriales_superadmin_grid"></div>
  </div>

  <div class="videotutoriales_superadmin_modal" id="videotutoriales_superadmin_modal">
    <div class="videotutoriales_superadmin_modal-content">
      <span class="videotutoriales_superadmin_close">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </span>
      <div class="videotutoriales_superadmin_video-container">
        <div class="videotutoriales_superadmin_loading"></div>
        <iframe allowfullscreen></iframe>
      </div>
      <div class="videotutoriales_superadmin_modal-info">
        <h3 class="videotutoriales_superadmin_modal-title"></h3>
        <p class="videotutoriales_superadmin_modal-description"></p>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    const videos = <?php echo json_encode($lista_videos); ?>;

    // Populate videos
    videos.forEach(video => {
      const videoId = video.url.split('/').pop();
      const card = `
        <div class="videotutoriales_superadmin_card" data-video="${video.url}" data-title="${video.title}" data-description="${video.description}">
          <div class="videotutoriales_superadmin_thumbnail">
            <img src="${video.thumbnail}" alt="${video.title}">
            <div class="videotutoriales_superadmin_play"></div>
          </div>
          <div class="videotutoriales_superadmin_content">
            <h3 class="videotutoriales_superadmin_title">${video.title}</h3>
            <div class="videotutoriales_superadmin_duration">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="8" cy="8" r="6"></circle>
                <path d="M8 4.5v3.5l2.5 2.5"></path>
              </svg>
              Tutorial Guiado
            </div>
          </div>
        </div>
      `;
      $('#videotutoriales_superadmin_grid').append(card);
    });

    // Handle video clicks
    $('.videotutoriales_superadmin_card').click(function() {
      const videoUrl = $(this).data('video');
      const videoId = videoUrl.split('/').pop();
      const title = $(this).data('title');
      const description = $(this).data('description');
      const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
      
      // Update modal content
      $('.videotutoriales_superadmin_modal-title').text(title);
      $('.videotutoriales_superadmin_modal-description').text(description);
      
      // Show loading animation
      $('.videotutoriales_superadmin_loading').show();
      
      // Set iframe src and show modal
      $('#videotutoriales_superadmin_modal iframe').attr('src', embedUrl);
      $('#videotutoriales_superadmin_modal').fadeIn(300);
      
      // Enable scrolling prevention
      $('body').css('overflow', 'hidden');
    });

    // Handle iframe load
    $('#videotutoriales_superadmin_modal iframe').on('load', function() {
      $('.videotutoriales_superadmin_loading').hide();
    });

    // Handle modal close
    $('.videotutoriales_superadmin_close').click(function() {
      closeModal();
    });

    // Close modal on escape key
    $(document).keyup(function(e) {
      if (e.key === "Escape") {
        closeModal();
      }
    });

    // Close modal on outside click
    $(window).click(function(event) {
      if (event.target == $('#videotutoriales_superadmin_modal')[0]) {
        closeModal();
      }
    });

    // Modal close function
    function closeModal() {
      $('#videotutoriales_superadmin_modal').fadeOut(300, function() {
        $('#videotutoriales_superadmin_modal iframe').attr('src', '');
        $('.videotutoriales_superadmin_loading').show();
      });
      $('body').css('overflow', 'auto');
    }

    // Add hover effect to close button
    $('.videotutoriales_superadmin_close').hover(
      function() {
        $(this).css('transform', 'rotate(90deg) scale(1.1)');
      },
      function() {
        $(this).css('transform', 'rotate(90deg)');
      }
    );

    // Add smooth scroll to grid items
    $('.videotutoriales_superadmin_grid').on('scroll', function() {
      requestAnimationFrame(function() {
        $('.videotutoriales_superadmin_card').each(function() {
          const rect = $(this)[0].getBoundingClientRect();
          if (rect.top >= 0 && rect.bottom <= window.innerHeight) {
            $(this).addClass('in-view');
          }
        });
      });
    });

    // Initialize tooltips for video cards
    $('.videotutoriales_superadmin_card').each(function() {
      $(this).attr('title', 'Click para reproducir el video');
    });

    // Add keyboard navigation for video cards
    $('.videotutoriales_superadmin_card').attr('tabindex', '0').on('keypress', function(e) {
      if (e.which === 13 || e.which === 32) {
        $(this).click();
      }
    });

    // Manejo de orientación en dispositivos móviles
    window.addEventListener('orientationchange', function() {
      setTimeout(function() {
        window.scrollTo(0, 0);
        adjustModalPosition();
      }, 200);
    });

    // Ajustar posición del modal en dispositivos móviles
    function adjustModalPosition() {
      if (window.innerWidth <= 768) {
        const modalContent = $('.videotutoriales_superadmin_modal-content');
        const windowHeight = window.innerHeight;
        const contentHeight = modalContent.height();
        
        if (contentHeight > windowHeight) {
          modalContent.css({
            'top': '0',
            'transform': 'translate(-50%, 0)',
            'height': '100%',
            'overflow-y': 'auto'
          });
        } else {
          modalContent.css({
            'top': '50%',
            'transform': 'translate(-50%, -50%)',
            'height': 'auto',
            'overflow-y': 'visible'
          });
        }
      }
    }

    // Detectar gestos táctiles para cerrar el modal
    let touchStartY = 0;
    $('.videotutoriales_superadmin_modal').on('touchstart', function(e) {
      touchStartY = e.originalEvent.touches[0].clientY;
    });

    $('.videotutoriales_superadmin_modal').on('touchmove', function(e) {
      const touchCurrentY = e.originalEvent.touches[0].clientY;
      const diff = touchStartY - touchCurrentY;

      if (Math.abs(diff) > 100) {
        closeModal();
      }
    });
  });
</script>
</body>
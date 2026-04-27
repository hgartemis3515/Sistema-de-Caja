(t=>{t.fn.loadingFacturalaYa=function(o,e){e=t.extend({message:"Procesando...",submessage:"Esto puede tomar unos segundos...",backgroundColor:"rgba(255, 255, 255, 0.8)",spinnerColor:"#4f46e5",textColor:"#111827",zIndex:9999},e);let n=`
        <div class="custom-loading-overlay">
          <div class="loading-content">
            <div class="spinner-container">
              <div class="spinner"></div>
            </div>
            <h3>${e.message}</h3>
            <p>${e.submessage}</p>
          </div>
        </div>
      `,i=`
        <style>
          .custom-loading-overlay {
            position: absolute;
            inset: 0;
            background: ${e.backgroundColor};
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: ${e.zIndex};
          }
          .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
          }
          .spinner-container {
            position: relative;
            width: 48px;
            height: 48px;
            margin-bottom: 1rem;
          }
          .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e0e7ff;
            border-top-color: ${e.spinnerColor};
            border-radius: 50%;
            animation: spin 1s linear infinite;
          }
          .loading-content h3 {
            margin-bottom: 0.25rem;
            color: ${e.textColor};
            font-weight: 600;
          }
          .loading-content p {
            color: #6b7280;
            font-size: 0.875rem;
          }
          @keyframes spin {
            to { transform: rotate(360deg); }
          }
        </style>
      `;return this.each(function(){var e=t(this);"show"===o?("static"===e.css("position")&&e.css("position","relative"),e.append(i+n)):"hide"===o&&e.find(".custom-loading-overlay").remove()})}})(jQuery);
{{-- Shared by application, authentication, and settings layouts. --}}
<style>
@supports (-webkit-touch-callout: none) {
  html { -webkit-text-size-adjust:100%; }
  html body input:not([type="checkbox"]):not([type="radio"]):not([type="range"]):not([type="hidden"]):not([type="submit"]):not([type="button"]),
  html body select,
  html body textarea,
  html body.mobile-app-density .main-scroll .page-wrap .form-control:not(.mobile-density-plain),
  html body.mobile-app-density .main-scroll .page-wrap .form-select:not(.mobile-density-plain),
  html body.mobile-app-density .modal .form-control:not(.mobile-density-plain),
  html body.mobile-app-density .modal .form-select:not(.mobile-density-plain) {
    font-size:16px !important;
  }
  html body input[type="date"],
  html body input[type="datetime-local"],
  html body input[type="time"] {
    min-width:0;
    max-width:100%;
    box-sizing:border-box;
  }
  html body .main-scroll,
  html body .table-responsive,
  html body .modal-body,
  html body .sheet-body {
    touch-action:auto !important;
    -webkit-overflow-scrolling:touch;
  }
  html body .table-responsive { max-width:100%; overflow-x:auto; }

  @media (max-width:767.98px) {
    html body.eprospek-login-page { overflow-y:auto !important; }
    html body .eprospek-login-shell {
      height:auto !important;
      min-height:100vh;
      min-height:100dvh;
      overflow:visible !important;
    }
    html body .page-wrap {
      padding-left:max(12px,env(safe-area-inset-left)) !important;
      padding-right:max(12px,env(safe-area-inset-right)) !important;
    }
    html body.mobile-app-density .header.d-md-none.mobile-app-header:not(.is-scroll-hidden) {
      height:calc(64px + env(safe-area-inset-top)) !important;
      min-height:calc(64px + env(safe-area-inset-top)) !important;
      max-height:none !important;
      padding-top:env(safe-area-inset-top) !important;
    }
    html body .modal {
      height:var(--ios-visible-height,100dvh) !important;
      top:var(--ios-visible-top,0px) !important;
      bottom:auto !important;
      padding:10px max(10px,env(safe-area-inset-right)) max(10px,env(safe-area-inset-bottom)) max(10px,env(safe-area-inset-left)) !important;
      overflow-y:auto !important;
    }
    html body .modal .modal-dialog {
      width:auto !important;
      max-width:100% !important;
      margin:0 auto !important;
    }
    html body .modal .modal-dialog-scrollable {
      height:calc(var(--ios-visible-height,100dvh) - 30px) !important;
      max-height:calc(var(--ios-visible-height,100dvh) - 30px) !important;
    }
    html body .modal .modal-dialog-scrollable .modal-content { max-height:100% !important; }
    html body .modal .modal-body {
      min-height:0;
      max-height:calc(var(--ios-visible-height,100dvh) - 150px) !important;
      overflow-y:auto !important;
    }
  }
}
</style>
<script src="{{ asset('js/ios-compat.js') }}" defer></script>

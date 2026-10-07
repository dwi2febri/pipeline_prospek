@if(isset($assignmentWarningMap[$p->id]))
  <div class="assignment-warning">
    <div class="assignment-warning-title">
      <i class="bi bi-arrow-left-right" aria-hidden="true"></i>
      Pindah ke {{ $assignmentWarningMap[$p->id]['position'] }}
    </div>
    <div class="assignment-warning-note">
      Produk {{ $assignmentWarningMap[$p->id]['product'] }} tidak sesuai jabatan saat ini.
    </div>
  </div>
  @if($canManageAssignment)
    <div class="assignment-reassign-note">Penugasan lama tetap tercatat sampai ditugaskan ulang.</div>
  @endif
@endif
